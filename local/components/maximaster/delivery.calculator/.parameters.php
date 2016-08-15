<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "DELIVERY_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID доставки, либо SID в случае обработчика",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ),
        "LOCATION_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID местоположения для доставки",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "REFRESH" => "Y",
        )
    )
);
?>