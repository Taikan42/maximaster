<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Оформить заказ");
?>
<? if (!empty($arResult)): ?>
    <div class="order_submit">
        <? if ($arResult["CHECK"]): ?>
            <div>
                <p>Уважаемый/ая <? echo $arResult["NAME"] ?>, ваш заказ принят. На ваш Email
                    (<? echo $arResult["EMAIL"] ?>) отправленно письмо</p>
            </div>
        <? else: ?>
            <div>
                <p>Ошибка!!!</p>
                <? foreach ($arResult["ERROR"] as $arItem): ?>
                    <div>
                        <p><? echo $arItem ?></p>
                    </div>
                <? endforeach ?>
            </div>
        <? endif ?>
    </div>
<? endif ?>