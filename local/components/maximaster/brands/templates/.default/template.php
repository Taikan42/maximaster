<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? if (!empty($arResult)): ?>
    <section class="brands">
        <ul>
            <? if ($arResult["MULTIPLE"]): ?>
                <? foreach ($arResult["BRAND"] as $arItem): ?>
                    <li>
                        <a href="<? echo "/catalog/list.php?BRAND=" . $arItem["XML_ID"]; ?>">
                            <? echo $arItem["NAME"]; ?>
                        </a>
                    </li>
                <? endforeach ?>
            <? else: ?>
                <li>
                    <a href="<? echo "/catalog/list.php?BRAND=" . $arResult["XML_ID"]; ?>">
                        <? echo $arResult["BRAND"]; ?>
                    </a>
                </li>
            <? endif ?>
        </ul>
    </section>
<? endif ?>
