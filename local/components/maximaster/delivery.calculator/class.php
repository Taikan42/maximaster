<? namespace Maximaster;

use Bitrix\Sale\Payment;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class DeliveryCalc extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['DELIVERY_ID'] = trim($arParams['DELIVERY_ID']);
        if ($arParams['DELIVERY_ID'] == '')
            $arParams['DELIVERY_ID'] = 0;

        $arParams['LOCATION_ID'] = trim($arParams['LOCATION_ID']);
        if ($arParams['LOCATION_ID'] == '')
            $arParams['LOCATION_ID'] = 0;

        return $arParams;
    }

    public function executeComponent()
    {
        try{
            $this->arResult["COST"] = $this->getCost($this->arParams["DELIVERY_ID"], $this->arParams["LOCATION_ID"]);
        } catch (\Exception $e){
            $this->arResult["ERROR"] = $e->getMessage();
        }
        $this->includeComponentTemplate();
        return $this->arResult;
    }

    public function getCost($DeliveryID, $LocationID)
    {
        $Cost = 0;
        $delres = \CSaleDelivery::GetList(
            array(),
            array(
                "ID" => $DeliveryID
            ),
            false,
            false,
            array()
        );
        if ($delob = $delres->Fetch()) {
            $Cost = $delob["PRICE"];
        } else {
            $Cost = $this->CalculateDeliveryHandler($DeliveryID, $LocationID);
        }
        return $Cost;
    }
    private function CalculateDeliveryHandler($DeliveryID, $LocationID){
        $BasketItems = \CSaleBasket::GetList(
            array(),
            array(
                "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
            false,
            false,
            array("QUANTITY", "PRICE")
        );
        $Total = 0;
        while ($arItems = $BasketItems->Fetch()) {
            $Total += (int)$arItems["QUANTITY"] * $arItems["PRICE"];
        }
        $arOrder = array(
            "WEIGHT" => "0",
            "PRICE" => $Total,
            "LOCATION_FROM" => \COption::GetOptionInt('sale', 'location'),
            "LOCATION_TO" => $LocationID
        );
        $currency = \CSaleLang::GetLangCurrency(SITE_ID);
        $dbHandler = \CSaleDeliveryHandler::GetBySID($DeliveryID);
        if ($arHandler = $dbHandler->Fetch()) {
            $arProfiles = \CSaleDeliveryHandler::GetHandlerCompability($arOrder, $arHandler);
            if (is_array($arProfiles) && count($arProfiles) > 0) {
                $arProfiles = array_keys($arProfiles);
                $arReturn = \CSaleDeliveryHandler::CalculateFull(
                    $DeliveryID, // идентификатор службы доставки
                    $arProfiles[0], // идентификатор профиля доставки
                    $arOrder, // заказ
                    $currency // валюта, в которой требуется вернуть стоимость
                );
                if ($arReturn["RESULT"] == "OK") {
                    return $arReturn["VALUE"];
                } else {
                    throw new \Exception('Не удалось рассчитать стоимость доставки! ');
                }
            } else {
                throw new \Exception('Невозможно доставить заказ!');
            }
        } else {
            throw new \Exception('Обработчик не найден!');
        }
    }
}