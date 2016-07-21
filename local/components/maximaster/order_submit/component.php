<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arPost = $_POST;
$initialsCh = null;
$maskPhoneCh = null;
$maskEmailCh = null;
$arResult["ERROR"] = array();
if ($arPost["name"] and $arPost["surname"] and $arPost["middle_name"]) {
    $initialsCh = true;
    $arResult["NAME"] = $arPost["surname"] . " " . $arPost["name"] . " " . $arPost["middle_name"];
} else {
    $initialsCh = false;
}
if (filter_var($arPost["email"], FILTER_VALIDATE_EMAIL)) {
    $maskEmailCh = true;
} else {
    $maskEmailCh = false;
    $arResult["ERROR"][] = "Email(" . $arPost["email"] . ") некорректен";
}
if (preg_match('/^\+[7]\([0-9]{3}\) [0-9]{3}[-][0-9]{2}[-][0-9]{2}$/', $arPost["phone"])) {
    $maskPhoneCh = true;
} else {
    $maskPhoneCh = false;
    $arResult["ERROR"][] = "Телефон(" . $arPost["phone"] . ") некорректен";
}
if ($initialsCh and $maskEmailCh and $maskPhoneCh) {
    $arResult["CHECK"] = true;
    $arResult["EMAIL"] = $arPost["email"];
    $delivery = "";
    $payment = "";
    switch ($arPost["delivery"]) {
        case "COURIER":
            $delivery = "Курьер";
            break;
        case "MAIL":
            $delivery = "Почта России";
            break;
        case "PICKUP":
            $delivery = "Самовывоз";
            break;
    }
    if ($arPost["payment_type"] === "CASH") {
        $arResult["PAYMENT"] = "Наличные";
    } else {
        switch ($arPost["non-cash"]) {
            case "SBER":
                $payment = "Сбербанк";
                break;
            case "GASPROM":
                $payment = "Газпромбанк";
                break;
            case "VISA":
                $payment = "VISA";
                break;
            case "PAYPAL":
                $payment = "PayPal";
                break;
        }
    }
    if (CModule::IncludeModule("sale")) {
        $arFields = array(
            "LID" => "s1",
            "PERSON_TYPE_ID" => 1,
            "PAYED" => "N",
            "CANCELED" => "N",
            "STATUS_ID" => "N",
            "PRICE" => 100,
            "CURRENCY" => "RUB",
            "USER_ID" => IntVal($USER->GetID()),
            "DISCOUNT_VALUE" => 0,
            "TAX_VALUE" => 0.0,
            "USER_DESCRIPTION" => ""
        );

        $ORDER_ID = CSaleOrder::Add($arFields);
        CSaleBasket::OrderBasket($ORDER_ID);

        $dbBasketItems = CSaleBasket::GetList(
            array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "DELAY" => "N",
                "ORDER_ID" => "NULL"
            ),
            false,
            false,
            array()
        );
        $basked = "Корзина:<br /><table border='1'>";
        $basked = $basked . "<tr><td align='center'>Товар</td><td align='center'>Цена</td><td align='center'>Кол-во</td><td align='center'>Сумма</td></tr>";
        while ($arItem = $dbBasketItems->Fetch()) {
            $pr = (int)$arItem["QUANTITY"] * $arItem["PRICE"];
            $num = (int)$arItem["QUANTITY"];
            $basked = $basked . "<tr><td align='left'>" . "<a href='" . $arItem["DETAIL_PAGE_URL"] . "'>" . $arItem["NAME"] . "</a></td><td align='left'>" . $arItem["PRICE"] . "</td><td align='left'>" . $num . "</td><td align='left'>" . $pr . "</td></tr>";
        }
        $basked = $basked . "</table>";
        $arEventFields = array(
            "NAME" => $arResult["NAME"],
            "M_MAIL" => $arParams["SENDERS_EMAIL"],
            "F_MAIL" => $arPost["email"],
            "PHONE" => $arPost["phone"],
            "DELIVERY" => $delivery,
            "PAYMENT" => $payment,
            "ATTRIBUTES" => $basked
        );
        CEvent::Send("USER_ORDER_SUBMIT", SITE_ID, $arEventFields);
        $arEventFields["F_MAIL"] = $arParams["MANAGERS_EMAIL"];
        $arEventFields["COMMENT"] = $arPost["comments"];
        CEvent::Send("MANAGER_ORDER_SUBMIT", SITE_ID, $arEventFields);
    }
} else {
    $arResult["CHECK"] = false;
}
$this->IncludeComponentTemplate();