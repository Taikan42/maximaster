<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arPost = $_POST;
$initialsCh = null;
$maskPhoneCh = null;
$maskEmailCh = null;
$arResult["ERROR"] = array();
/*Проверка ФИО*/
if ($arPost["name"] and $arPost["surname"] and $arPost["middle_name"]) {
    $initialsCh = true;
    $arResult["NAME"] = $arPost["surname"] . " " . $arPost["name"] . " " . $arPost["middle_name"];
} else {
    $initialsCh = false;
}
/*Проверка email*/
if (filter_var($arPost["email"], FILTER_VALIDATE_EMAIL)) {
    $maskEmailCh = true;
} else {
    $maskEmailCh = false;
    $arResult["ERROR"][] = "Email(" . $arPost["email"] . ") некорректен";
}
/*Проверка телефонф*/
if (preg_match('/^\+[7]\([0-9]{3}\) [0-9]{3}[-][0-9]{2}[-][0-9]{2}$/', $arPost["phone"])) {
    $maskPhoneCh = true;
} else {
    $maskPhoneCh = false;
    $arResult["ERROR"][] = "Телефон(" . $arPost["phone"] . ") некорректен";
}
if ($initialsCh and $maskEmailCh and $maskPhoneCh) {
        $delivery = array();
        if ($arPost["DeliveryHandler-SID=" . $arPost["Delivery"]]) {
            $BasketItems = CSaleBasket::GetList(
                array(),
                array(
                    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                    "LID" => SITE_ID,
                    "ORDER_ID" => "NULL"
                ),
                false,
                false,
                array("QUANTITY", "PRICE")
            );
            $Total = 0;
            while ($arItems = $BasketItems->Fetch()) {
                $Total += (int)$arItems["QUANTITY"] * $arItems["PRICE"];
            }
            $arOrder = array(
                "WEIGHT" => "0",
                "PRICE" => $Total,
                "LOCATION_FROM" => COption::GetOptionInt('sale', 'location'),
                "LOCATION_TO" => $arPost["location"]
            );
            $currency = CSaleLang::GetLangCurrency(SITE_ID);
            $dbHandler = CSaleDeliveryHandler::GetBySID($arPost["Delivery"]);
            if ($arHandler = $dbHandler->Fetch()) {
                $delivery["NAME"] = $arHandler["NAME"];
                $delivery["ID"] = $arHandler["SID"];
                $arProfiles = CSaleDeliveryHandler::GetHandlerCompability($arOrder, $arHandler);
                if (is_array($arProfiles) && count($arProfiles) > 0) {
                    $arProfiles = array_keys($arProfiles);
                    $arReturn = CSaleDeliveryHandler::CalculateFull(
                        $arPost["Delivery"], // идентификатор службы доставки
                        $arProfiles[0], // идентификатор профиля доставки
                        $arOrder, // заказ
                        $currency // валюта, в которой требуется вернуть стоимость
                    );

                    if ($arReturn["RESULT"] == "OK") {
                        $delivery["PRICE"] = $arReturn["VALUE"];
                    } else {
                        $arResult["ERROR"][] = 'Не удалось рассчитать стоимость доставки! ';
                    }
                } else {
                    $arResult["ERROR"][] = 'Невозможно доставить заказ!';
                }
            } else {
                $arResult["ERROR"][] = 'Обработчик не найден!';
            }
        } else {
            $delres = CSaleDelivery::GetList(
                array(),
                array(),
                false,
                false,
                array()
            );
            if ($delob = $delres->Fetch()) {
                $delivery["ID"] = $delob["ID"];
                $delivery["NAME"] = $delob["NAME"];
                $delivery["PRICE"] = $delob["PRICE"];
            }
        }
        $payment = array();
        $payres = CSalePaySystem::GetList(
            array(),
            array(
                "ID" => $arPost["Payment"]
            ),
            false,
            false,
            array("ID", "NAME")
        );
        if ($payob = $payres->Fetch()) {
            $payment["ID"] = $payob["ID"];
            $payment["NAME"] = $payob["NAME"];
        }
        if(!$arResult["ERROR"]){
            $arResult["CHECK"] = true;
            $arFields = array(
                "LID" => "s1",
                "PERSON_TYPE_ID" => 1,
                "PAYED" => "N",
                "CANCELED" => "N",
                "STATUS_ID" => "N",
                "PRICE" => 100,
                "CURRENCY" => "RUB",
                "USER_ID" => IntVal($USER->GetID()),
                "PAY_SYSTEM_ID" => $payment["ID"],
                "DELIVERY_ID" => $delivery["ID"],
                "PRICE_DELIVERY" => $delivery["PRICE"],
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
                    "ORDER_ID" => $ORDER_ID
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
            $basked = $basked . "<tr><td align='left'>" . $delivery["NAME"] . "</a></td><td align='left'> </td><td align='left'> </td><td align='left'>" . $delivery["PRICE"] . "</td></tr>";
            $basked = $basked . "</table>";
            $arEventFields = array(
                "NAME" => $arResult["NAME"],
                "M_MAIL" => $arParams["SENDERS_EMAIL"],
                "F_MAIL" => $arPost["email"],
                "PHONE" => $arPost["phone"],
                "DELIVERY" => $delivery["NAME"],
                "PAYMENT" => $payment["NAME"],
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