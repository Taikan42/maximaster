<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle($arResult["SECTION"]["NAME"]);
?>
<? if (!empty($arResult)): ?>
    <section class="catalog">
        <? if ($arResult["CHECK_SECTION"]): ?>
            <div class="flex-cont section">
                <div class="flex-block left-block">
                    <? echo CFile::ShowImage($arResult["SECTION"]["PICTURE"], 200, 200, "border=0", "", false); ?>
                </div>
                <div class="flex-block right-block">
                    <h3><? echo $arResult["SECTION"]["NAME"] ?></h3>
                    <p><? echo $arResult["SECTION"]["DESCRIPTION"] ?></p>
                </div>
            </div>
        <? endif ?>
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
                    <a href="#" class="buy_botton1" onclick="add2basket(<?echo $arItem["ID"]; ?>), PopUpShow()">в корзину</a>
                    <p><? echo $arItem["PREVIEW_TEXT"] ?></p>
                </div>
            </div>
        <? endforeach ?>
        <div class="popup" onclick="PopUpHide()" id="popup1">
            <div class="popup-content">
                <a href="#" onclick="PopUpHide()"> Продолжить </a>
                <a href="/local/templates/maximaster/cart/index.php">К корзине</a>
            </div>
        </div>
    </section>
<? endif ?>
<script type="text/javascript">
    $(document).ready(function(){
        //Скрыть PopUp при загрузке страницы
        PopUpHide();
    });
    //Функция отображения PopUp
    function PopUpShow(){
        $("#popup1").show();
    }
    //Функция скрытия PopUp
    function PopUpHide(){
        $("#popup1").hide();
    }
    function add2basket(ID)
    {
        var id = ID;
        var clv = 1;
        $.ajax({
            type: 'POST',
            url: "/local/templates/maximaster/cart/addbasket_ajax.php",
            data: {id: ID, quantity: clv,}
        });
    }
</script>
