<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? if (!empty($arResult)): ?>
    <section class="brands">
        <ul>
            <? foreach ($arResult["BRAND"] as $arItem): ?>
                <li>
                    <a href="<? echo "/catalog/list/" . $arItem["XML_ID"]; ?>">
                        <? echo $arItem["NAME"]; ?>
                    </a>
                </li>
            <? endforeach ?>
        </ul>
    </section>
<? endif ?>
