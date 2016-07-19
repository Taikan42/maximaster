<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<? $APPLICATION->IncludeComponent("maximaster:order", ".default",
    array("URL_ORDER_SUBMIT" => '/local/templates/maximaster/cart/order_submit.php'),
    false
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
