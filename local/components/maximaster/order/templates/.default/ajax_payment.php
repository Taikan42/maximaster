<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? foreach ($arResult["PAY_SYSTEM"] as $arItem): ?>
    <div class="radio_wrap">
        <input type="radio" name="Payment" id="pay-<? echo $arItem["ID"] ?>" value="<? echo $arItem["ID"] ?>"/>
        <label for="pay-<? echo $arItem["ID"] ?>">
            <? echo $arItem["LOGOTIP"] ?>
        </label>
    </div>
<? endforeach; ?>