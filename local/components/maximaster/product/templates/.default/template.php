<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["NAME"]);
?>
<?if (!empty($arResult)):?>
    <section class="product">
        <h3><?echo $arResult["NAME"]?></h3>
        <div class="flex-cont">
            <div class="flex-block left-block">
                <? echo CFile::ShowImage($arResult["PICTURE"], 200, 200, "border=0", "", false); ?>
            </div>
            <div class="flex-block right-block">
                <p class="price"><?echo $arResult["PRICE"]?> р.</p>
                <ul>
                    <li>Количество: <?echo $arResult["NUMBER"]?></li>
                    <li>Страна: <?echo $arResult["COUNTRY"]?></li>
                    <li>Бренд: <?echo $arResult["BREND"]?></li>
                </ul>
            </div>
        </div>
        <p><?echo $arResult["TEXT"]?></p>
    </section>
<?endif?>