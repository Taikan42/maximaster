<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->addExternalCss("/local/styles.css");?>
<?if (!empty($arResult)):?>
<section class="catalog">
    <div class="flex-cont">
        <div class="flex-block left-block">
            <? echo CFile::ShowImage($arResult["SECTION"]["PICTURE"], 200, 200, "border=0", "", true); ?>
        </div>
        <div class="flex-block right-block">
            <h3 class="title"><?echo $arResult["SECTION"]["NAME"]?></h3>
            <p><?echo $arResult["SECTION"]["DESCRIPTION"]?></p>
        </div>
    </div>
    <?foreach($arResult["ELEMENT"] as $arItem):?>
        <div class="flex-cont">
            <div class="flex-block left-block">
                <? echo CFile::ShowImage($arItem["PREVIEW_PICTURE"], 100, 100, "border=0", "", true); ?>
            </div>
            <div class="flex-block right-block">
                <h3 class="title"><?echo $arItem["NAME"]?></h3>
                <p><?echo $arItem["PRICE"]?></p>
                <p><?echo $arItem["PREVIEW_TEXT"]?></p>
            </div>
        </div>
    <?endforeach?>
</section>
<?endif?>