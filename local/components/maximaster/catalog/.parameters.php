<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "4",
            "REFRESH" => "Y",
        ),
        "BASKET_PAGE" => array(
            "PARENT" => "BASE",
            "NAME" => "Страница корзины",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ),
        "SECTIONS" => array(
            "PARENT" => "BASE",
            "NAME" => "Вывести разделы инфоблока",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
    )
);
?>