<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "SENDERS_EMAIL" => array(
            "PARENT" => "BASE",
            "NAME" => "Email отправителя",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "a.stankevich@maximaster.ru",
            "REFRESH" => "Y",
        ),
        "MANAGERS_EMAIL" => array(
            "PARENT" => "BASE",
            "NAME" => "Email менеджера",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "a.stankevich@maximaster.ru",
            "REFRESH" => "Y",
        ),
    )
);
?>