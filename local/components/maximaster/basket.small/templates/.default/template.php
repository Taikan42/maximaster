<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? if (!empty($arResult)): ?>
    <? if ($arResult['HIDE'] != "Y"): ?>
        <a href="<?echo $arResult['PATH_TO_BASKET'] ?>" class="mm-basket">
            <div class="mm-basket-block">
                <span class="head">Корзина</span>
                <? if (!empty($arResult['NUM_PRODUCTS'])): ?>
                    <span>Товаров - <?echo $arResult['NUM_PRODUCTS'] ?></span>
                <? endif ?>
                <? if (!empty($arResult['TOTAL_PRICE'])): ?>
                    <span>Сумма - <?echo $arResult['TOTAL_PRICE'] ?></span>
                <? endif ?>
            </div>
        </a>
    <? endif ?>
<? endif ?>
