<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class MMBasketSmall extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['PATH_TO_BASKET'] = trim($arParams['PATH_TO_BASKET']);
        if ($arParams['PATH_TO_BASKET'] == '')
            $arParams['PATH_TO_BASKET'] = SITE_TEMPLATE_PATH . '/cart/index.php';

        $arParams['PATH_TO_ORDER'] = trim($arParams['PATH_TO_ORDER']);
        if ($arParams['PATH_TO_ORDER'] == '')
            $arParams['PATH_TO_ORDER'] = SITE_TEMPLATE_PATH . '/cart/order.php';

        $arParams['SHOW_NUM_PRODUCTS'] = trim($arParams['SHOW_NUM_PRODUCTS']);
        if ($arParams['SHOW_NUM_PRODUCTS'] != 'N')
            $arParams['SHOW_NUM_PRODUCTS'] = 'Y';

        $arParams['SHOW_TOTAL_PRICE'] = trim($arParams['SHOW_TOTAL_PRICE']);
        if ($arParams['SHOW_TOTAL_PRICE'] != 'N')
            $arParams['SHOW_TOTAL_PRICE'] = 'Y';

        return $arParams;
    }

    private function getNumProduct()
    {
        $BasketItems = CSaleBasket::GetList(
            array(),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
            array()
        );
        return $BasketItems;
    }

    private function getTotalPrice()
    {
        $BasketItems = CSaleBasket::GetList(
            array(),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
            false,
            false,
            array("QUANTITY", "PRICE")
        );
        $Total = 0;
        while ($arItems = $BasketItems->Fetch()) {
            $Total += $arItems["QUANTITY"] * $arItems["PRICE"];
        }
        return $Total;
    }

    public function executeComponent()
    {
        if (CModule::IncludeModule("sale")) {
            $this->arResult['PATH_TO_BASKET'] = $this->arParams['PATH_TO_BASKET'];
            $this->arResult['PATH_TO_ORDER'] = $this->arParams['PATH_TO_ORDER'];

            if ($_SERVER['REQUEST_URI'] != $this->arResult['PATH_TO_BASKET']) {
                if ($this->arParams['SHOW_NUM_PRODUCTS'] == "Y") {
                    $this->arResult["NUM_PRODUCTS"] = $this->getNumProduct();
                }
                if ($this->arParams['SHOW_TOTAL_PRICE'] == "Y") {
                    $this->arResult["TOTAL_PRICE"] = $this->getTotalPrice();
                }
                $this->arResult['HIDE'] = 'N';
            } else {
                $this->arResult['HIDE'] = 'Y';
            }
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }
} ?>