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
                <p class="price"><?echo $arResult["PRICE"]?> <?echo $arResult["CURRENCY"]?>.</p>
                <div class="options">
                    <ul>
                        <li>Количество: <? echo $arResult["NUMBER"] ?></li>
                        <li>Страна: <? echo $arResult["COUNTRY"] ?></li>
                        <li>Бренд: <? echo $arResult["BRAND"] ?></li>
                    </ul>
                </div>
                <div class="form">
                    <input type="text" class="QUANTITY" name="quantity" value="1" size="5">
                    <button id="<?echo $arResult["ID"];?>">в корзину</button>
                </div>
            </div>
        </div>
        <p><?echo $arResult["TEXT"]?></p>
        <div class="popup" onclick="PopUpHide()" id="popup1">
            <div class="popup-content">
                <a href="#" onclick="PopUpHide()"> Продолжить </a>
                <a href="/local/templates/maximaster/cart/index.php">К корзине</a>
            </div>
        </div>
    </section>
<?endif?>
