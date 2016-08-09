<? namespace Maximaster;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class FormOrder extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['URL_ORDER_SUBMIT'] = trim($arParams['URL_ORDER_SUBMIT']);
        if ($arParams['URL_ORDER_SUBMIT'] == '')
            $arParams['URL_ORDER_SUBMIT'] = '/';

        return $arParams;
    }

    public function executeComponent()
    {
        $this->arResult['DELIVERY'] = $this->getDelivery();
        $this->includeComponentTemplate();
        return $this->arResult;
    }

    private function getDelivery()
    {
        $OrderFoDelivery = array(
            "SORT" => "ASC",
            "NAME" => "ASC"
        );
        $FilterFoDelivery = array(
            "LID" => SITE_ID,
            "ACTIVE" => "Y"
        );
        $arDelivery = array();
        $delres = \CSaleDelivery::GetList(
            $OrderFoDelivery,
            $FilterFoDelivery,
            false,
            false,
            array("ID", "NAME", "LOGOTIP")
        );
        $delID = array();
        while ($delob = $delres->Fetch()) {
            $ID = $delob["ID"];
            $arDelivery[$ID] = array(
                "HANDLER" => "N",
                "ID" => $ID,
                "LOGOTIP" => \CFile::ShowImage($delob["LOGOTIP"], 100, 100, "alt=\"" . $delob["NAME"] . "\"", "")
            );
        }
        $delres = \CSaleDeliveryHandler::GetList(
            $OrderFoDelivery,
            $FilterFoDelivery
        );
        while ($delob = $delres->Fetch()) {
            $ID = $delob["SID"];
            $arDelivery[$ID] = array(
                "HANDLER" => "Y",
                "ID" => $ID,
                "LOGOTIP" => \CFile::ShowImage($delob["LOGOTIP"], 100, 100, "alt=\"" . $delob["NAME"] . "\"", "")
            );
        }
        return $arDelivery;
    }
}