<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php"); ?>
<?
if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")) {
    if (isset($_POST['id']) && isset($_POST['quantity'])) {
        $PRODUCT_ID = intval($_POST['id']);
        $QUANTITY = intval($_POST['quantity']);
        $res = CIBlockElement::GetList(
            Array(),
            ["ID" => $PRODUCT_ID],
            false,
            false,
            ["ID", "IBLOCK_ID", "PROPERTY_COUNTRY", "PROPERTY_BRAND"]
        );
        $res = $res->fetch();
        if (!CModule::IncludeModule('highloadblock')) ;
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($res["IBLOCK_ID"])->fetch();
        $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
        $hlDataClass = $hldata['NAME'] . 'Table';
        $result = $hlDataClass::getList(array(
            'select' => array('UF_NAME'),
            'filter' => array('UF_XML_ID' => $res["PROPERTY_BRAND_VALUE"]),
        ));
        $result = $result->fetch();
        $PROP = array(
            array(
                "NAME" => "Страна",
                "VALUE" => $res["PROPERTY_COUNTRY_VALUE"]
            ),
            array(
                "NAME" => "Бренд",
                "VALUE" => $result["UF_NAME"]
            )
        );
        Add2BasketByProductID(
            $PRODUCT_ID,
            $QUANTITY,
            array(),
            $PROP
        );
        $APPLICATION->IncludeComponent(
            "bitrix:sale.basket.basket.line",
            "",
            Array(
                "HIDE_ON_BASKET_PAGES" => "Y",
                "PATH_TO_BASKET" => "/local/templates/maximaster/cart/index.php",
                "PATH_TO_ORDER" => "/local/templates/maximaster/cart/order.php",
                "PATH_TO_PERSONAL" => "",
                "PATH_TO_PROFILE" => "",
                "PATH_TO_REGISTER" => "",
                "POSITION_FIXED" => "N",
                "POSITION_HORIZONTAL" => "right",
                "POSITION_VERTICAL" => "top",
                "SHOW_AUTHOR" => "N",
                "SHOW_DELAY" => "N",
                "SHOW_EMPTY_VALUES" => "Y",
                "SHOW_IMAGE" => "Y",
                "SHOW_NOTAVAIL" => "N",
                "SHOW_NUM_PRODUCTS" => "Y",
                "SHOW_PERSONAL_LINK" => "N",
                "SHOW_PRICE" => "Y",
                "SHOW_PRODUCTS" => "Y",
                "SHOW_SUBSCRIBE" => "N",
                "SHOW_SUMMARY" => "Y",
                "SHOW_TOTAL_PRICE" => "Y"
            )
        );
    } else {
        echo "Нет параметров ";
    }
} else {
    echo "Не подключены модули";
}
?>
