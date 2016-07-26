<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Оформить заказ");
?>
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
                    <label for="user_comments">Коментарии: </label>
                </td>
                <td>
                    <textarea id="user_comments" name="comments" rows="3"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="delivery">Получение: </label>
                </td>
                <td>
                    <?$check = false;
                    foreach ($arResult["DELIVERY"] as $arItem): ?>
                        <div class="radio_wrap">
                            <input type="radio" name="Delivery" <?if(!$check) {echo "checked"; $check = true;}?>
                                   value="<? echo $arItem["ID"] ?>"/> <? echo $arItem["LOGOTIP"] ?>
                        </div>
                    <? endforeach; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="payment_type">Способ оплаты: </label>
                </td>
                <td>
                    <?$check = false;
                    foreach ($arResult["PAYMENT"] as $arItem): ?>
                        <div class="radio_wrap">
                            <input type="radio" name="Payment" <?if(!$check) {echo "checked"; $check = true;}?>
                                   value="<? echo $arItem["ID"] ?>"/> <? echo $arItem["LOGOTIP"] ?>
                        </div>
                    <? endforeach; ?>
                </td>
            </tr>
        </table>
        <input type="submit" value="Принять" disabled class="submit"/>
    </form>
</div>



        
