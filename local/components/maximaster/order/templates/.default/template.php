<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Оформить заказ");
?>
<? if (!empty($arResult)): ?>
    <div class="form-order">
        <form action="<? echo $arResult["ACTION_URL"] ?>" class="order" id="sale_order" method="post">
            <table>
                <tr>
                    <td>
                        <label for="name">Имя: </label>
                    </td>
                    <td>
                        <input type="text" id="name" name="name" class="form_text empty_field"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="surname">Фамилия: </label>
                    </td>
                    <td>
                        <input type="text" id="surname" name="surname" class="form_text empty_field"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="middle_name">Отчество: </label>
                    </td>
                    <td>
                        <input id="middle_name" name="middle_name" class="form_text empty_field"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="user_phone">Телефон: </label>
                    </td>
                    <td>
                        <input type="text" id="user_phone" name="phone" class="form_text empty_field"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="user_email">Email: </label>
                    </td>
                    <td>
                        <input type="text" placeholder="" id="user_email" name="email" class="empty_field"/>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Местонахождение: </span>
                    </td>
                    <td>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:sale.location.selector.search",
                            "",
                            Array(
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "",
                                "FILTER_BY_SITE" => "N",
                                "ID" => "",
                                "INITIALIZE_BY_GLOBAL_EVENT" => "",
                                "INPUT_NAME" => "location",
                                "JS_CALLBACK" => "",
                                "JS_CONTROL_GLOBAL_ID" => "",
                                "PROVIDE_LINK_BY" => "id",
                                "SHOW_DEFAULT_LOCATIONS" => "N",
                                "SUPPRESS_ERRORS" => "N"
                            )
                        ); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="user_comments">Коментарии: </label>
                    </td>
                    <td>
                        <textarea id="user_comments" name="comments" rows="3"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Получение: </span>
                    </td>
                    <td>
                        <? foreach ($arResult["DELIVERY"] as $arItem): ?>
                            <div class="radio_wrap">
                                <input type="radio" name="Delivery" id="del-<? echo $arItem["ID"] ?>"
                                       value="<? echo $arItem["ID"] ?>"/>
                                <label for="del-<? echo $arItem["ID"] ?>"><? echo $arItem["LOGOTIP"] ?></label>
                                <input type="hidden"
                                       name="permiss_types_payment-delID=<? echo $arItem["ID"] ?>"
                                value=<? echo implode(",", $arItem["PAYMENT_ID"]) ?>>
                                <? if ($arItem["HANDLER"] == "Y"): ?>
                                    <input type="hidden"
                                           name="DeliveryHandler-SID=<? echo $arItem["ID"] ?>"
                                    value= "true">
                                <? endif ?>
                            </div>
                        <? endforeach; ?>
                    </td>
                </tr>
                <tr class="payment hide">
                    <td>
                        <span>Способ оплаты: </span>
                    </td>
                    <td>
                        <? foreach ($arResult["PAYMENT"] as $arItem): ?>
                            <div class="radio_wrap payment_wrap paymentID-<? echo $arItem["ID"] ?> hide">
                                <input type="radio" name="Payment" id="pay-<? echo $arItem["ID"] ?>"
                                       value="<? echo $arItem["ID"] ?>"/>
                                <label for="pay-<? echo $arItem["ID"] ?>"><? echo $arItem["LOGOTIP"] ?></label>
                            </div>
                        <? endforeach; ?>
                    </td>
                </tr>
            </table>
            <input type="submit" value="Принять" class="submit"/>
        </form>
    </div>
<? endif ?>