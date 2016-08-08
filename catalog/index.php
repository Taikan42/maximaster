<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<? $APPLICATION->IncludeComponent("maximaster:catalog", ".default",
    array(
        "IBLOCK_ID" => IBLOCK_CATALOG,
        "BASKET_PAGE" => "/local/templates/maximaster/cart/index.php",
        "SECTIONS" => "Y"
    ),
    false
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>