<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<? $APPLICATION->IncludeComponent("maximaster:order_submit", ".default",
    array(
        "SENDERS_EMAIL" => "a.stankevich@maximaster.ru",
        "MANAGERS_EMAIL" =>"a.stankevich@maximaster.ru"
    ),
    false
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>