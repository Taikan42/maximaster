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
            "maximaster:basket.small",
            ".default",
            Array(
                "PATH_TO_BASKET" => "/local/templates/maximaster/cart/index.php",
                "PATH_TO_ORDER" => "/local/templates/maximaster/cart/order.php",
                "SHOW_NUM_PRODUCTS" => "Y",
                "SHOW_TOTAL_PRICE" => "Y"
            ));

    } else {
        echo "Нет параметров ";
    }
} else {
    echo "Не подключены модули";
}
?>
