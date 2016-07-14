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
                    <form action="" method="post">
                        <input type="text" class="input1" id="QUANTITY" name="quantity" value="1" size="5">
                        <input type="hidden" name="id" value=<?echo $arResult["ID"];?>>
                        <a href="#add2basket" class="buy_botton1" onclick="add2basket(<?echo $arResult["ID"];?>), PopUpShow()">в корзину</a>
                        <input type="hidden" name="country" id="COUNTRY" value="<?echo $arResult["COUNTRY"];?>">
                        <input type="hidden" name="brand" id="BRAND" value="<?echo $arResult["BRAND"];?>">
                    </form>
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
<script>
    $(document).ready(function(){
        PopUpHide();
    });
    function PopUpShow(){
        $("#popup1").show();
    }
    function PopUpHide(){
        $("#popup1").hide();
    }
    function add2basket(ID)
    {
        var clv = $("#QUANTITY").attr("value");
        var Country = $("#COUNTRY").attr("value");
        var Brand = $("#BRAND").attr("value");
        $.ajax({
            type: 'POST',
            url: "/local/templates/maximaster/cart/addbasket_ajax.php",
            data: {id: ID, quantity: clv, country: Country, brand: Brand}
        });
    }
</script>