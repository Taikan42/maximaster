<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:catalog.filter",
    "",
    Array(
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "FIELD_CODE" => array("", ""),
        "FILTER_NAME" => "arrFilter",
        "IBLOCK_ID" => "4",
        "IBLOCK_TYPE" => "catalog",
        "LIST_HEIGHT" => "5",
        "NUMBER_WIDTH" => "5",
        "PAGER_PARAMS_NAME" => "arrPager",
        "PRICE_CODE" => array("BASE"),
        "PROPERTY_CODE" => array("COUNTRY", ""),
        "SAVE_IN_SESSION" => "N",
        "TEXT_WIDTH" => "20"
    )
);?>
<? $APPLICATION->IncludeComponent("maximaster:catalog", ".default",
    array(
        "IBLOCK_ID" => "4", 
        "BASKET_PAGE" => "/local/templates/maximaster/cart/index.php"
    ),
    false
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
