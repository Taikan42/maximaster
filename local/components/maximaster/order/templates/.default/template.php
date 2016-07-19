<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Оформить заказ");
?>
<div class="form-order">
    <form action="" class="order" id="sale_order" method="post">
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
                    <span>Получение: </span>
                </td>
                <td>
                    <select name="delivery" class="delivery">
                        <option value="COURIER" selected>Курьерская доставка</option>
                        <option value="MAIL">Почта России</option>
                        <option value="PICKUP" >Самовывоз</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Способ оплаты: </span>
                </td>
                <td>
                    <select name="payment_type" class="payment_type">
                        <option value="NON_CASH" selected>Безналичный</option>
                        <option value="CASH" >Наличный</option>
                    </select>
                    <p>
                        <input type="radio" name="non-cash" value="SBER" checked /> <span>Сбербанк</span> </br>
                        <input type="radio" name="non-cash" value="GASPROM"/> <span>Газпромбанк</span> </br>
                        <input type="radio" name="non-cash" value="VISA"/> <span>VISA и MasterCard</span> </br>
                        <input type="radio" name="non-cash" value="PAYPAL"/> <span>PayPal</span> </br>
                    </p>
                </td>
            </tr>
        </table>
        <input type="submit" value="Принять" disabled class="submit"/>
    </form>
</div>



        
