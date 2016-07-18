<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Оформить заказ");
?>
<div class="form-order">
    <form action="" class="order" id="sale_order">
        <table>
            <tr>
                <td>
                    <label for="name">Имя: </label>
                </td>
                <td>
                    <input type="text" id="name" class="empty_field"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="surname">Фамилия: </label>
                </td>
                <td>
                    <input type="text" id="surname" class="empty_field"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="middle_name">Отчество: </label>
                </td>
                <td>
                    <input id="middle_name" class="empty_field"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="user_phone">Телефон: </label>
                </td>
                <td>
                    <input type="text" id="user_phone" class="empty_field"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="user_email">Email: </label>
                </td>
                <td>
                    <input type="text" placeholder="" id="user_email" class="empty_field"/>
                    <p></p>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Доставка: </span>
                </td>
                <td>
                    <select name="delivery">
                        <option value="s1" selected>Доставка</option>
                        <option value="s2" >Самовывоз</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Способ оплаты: </span>
                </td>
                <td>
                    <select name="payment_type">
                        <option value="s1" >Наличный</option>
                        <option value="s2" selected>Безналичный</option>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>



        
