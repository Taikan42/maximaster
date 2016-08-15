<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!empty($arResult["COST"])): ?>
    <span><? echo $arResult["COST"] ?> руб.</span>
<? endif; ?>
<?if (!empty($arResult["ERROR"])): ?>
<span><? echo $arResult["ERROR"] ?></span>
<? endif; ?>
