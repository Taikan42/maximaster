<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["NAME"]);
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
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
                        <li>Бренд: <? echo $arResult["BREND"] ?></li>
                    </ul>
                </div>
                <div class="form">
                    <form action="" method="post">
                        <input type="text" class="input1" id="QUANTITY" name="quantity" value="1" size="5">
                        <input type="hidden" name="id" value=<?echo $arResult["ID"];?>>
                        <a href="#add2basket" class="buy_botton1" onclick="add2basket(<?echo $arResult["ID"];?>)">в корзину</a>
                    </form>
                </div>
            </div>
        </div>
        <p><?echo $arResult["TEXT"]?></p>
    </section>
<?endif?>
<script>
    function add2basket(ID)
    {
        var id = ID;
        var clv = $("#QUANTITY").attr("value");
        $.ajax({
            type: 'POST',
            url: "/local/templates/maximaster/cart/addbasket_ajax.php",
            data: {id: ID, quantity: clv,}
        });
    }
</script>