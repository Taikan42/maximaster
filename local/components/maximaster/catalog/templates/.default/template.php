<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <section class="catalog">
        <? if (!empty($arResult["SECTION"])): ?>
            <? foreach ($arResult["SECTION"] as $arItem): ?>
                <a href="<? echo($arItem["SECTION_PAGE_URL"]) ?>">
                    <div class="flex-cont section">
                        <div class="flex-block left-block">
                            <? echo CFile::ShowImage($arItem["PICTURE"], 200, 200, "border=0", "", false); ?>
                        </div>
                        <div class="flex-block right-block">
                            <h3><? echo $arItem["NAME"] ?></h3>
                            <p><? echo $arItem["DESCRIPTION"] ?></p>
                        </div>
                    </div>
                </a>
            <? endforeach ?>
        <? endif ?>
        <? if (!empty($arResult["ELEMENT"])): ?>
            <? foreach ($arResult["ELEMENT"] as $arItem): ?>
                <div class="flex-cont">
                    <div class="flex-block left-block">
                        <a href="<? echo($arItem["DETAIL_PAGE_URL"]) ?>">
                            <? echo CFile::ShowImage($arItem["PREVIEW_PICTURE"], 100, 100, "border=0", "", false); ?>
                        </a>
                    </div>
                    <div class="flex-block right-block">
                        <h3><? echo($arItem["NAME"]); ?></h3>
                        <p class="price"><? echo $arItem["PRICE"] ?> <? echo $arItem["CURRENCY"] ?>.</p>
                        <button id="<? echo $arItem["ID"]; ?>">в корзину</button>
                        <p><? echo $arItem["PREVIEW_TEXT"] ?></p>
                    </div>
                </div>
            <? endforeach ?>
        <? endif ?>
        <? if (!empty($arResult["BASKET_PAGE"])): ?>
            <div class="popup" id="popup1">
                <div class="popup-content">
                    <a class="continue""> Продолжить </a>
                    <a href="<? echo $arResult["BASKET_PAGE"] ?>">К корзине</a>
                </div>
            </div>
        <? endif ?>
    </section>
<? endif ?>