<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<? $APPLICATION->IncludeComponent("maximaster:product", ".default",
    array("IBLOCK_ID" => IBLOCK_CATALOG),
    false
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>