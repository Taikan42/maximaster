    </div>
    <div class="flex-block right-block">
        <div id="basket-container">
            <?$APPLICATION->IncludeComponent(
                "maximaster:basket.small",
                ".default",
                Array(
                    "PATH_TO_BASKET" => "/local/templates/maximaster/cart/index.php",
                    "PATH_TO_ORDER" => "/local/templates/maximaster/cart/order.php",
                    "SHOW_NUM_PRODUCTS" => "Y",
                    "SHOW_TOTAL_PRICE" => "Y"
                ));
            ?>
            <?/*$APPLICATION->IncludeComponent(
                "bitrix:sale.basket.basket.line",
                "",
                Array(
                    "HIDE_ON_BASKET_PAGES" => "Y",
                    "PATH_TO_BASKET" => "/local/templates/maximaster/cart/index.php",
                    "PATH_TO_ORDER" => "/local/templates/maximaster/cart/order.php",
                    "PATH_TO_PERSONAL" => "",
                    "PATH_TO_PROFILE" => "",
                    "PATH_TO_REGISTER" => "",
                    "POSITION_FIXED" => "N",
                    "POSITION_HORIZONTAL" => "right",
                    "POSITION_VERTICAL" => "top",
                    "SHOW_AUTHOR" => "N",
                    "SHOW_DELAY" => "N",
                    "SHOW_EMPTY_VALUES" => "Y",
                    "SHOW_IMAGE" => "Y",
                    "SHOW_NOTAVAIL" => "N",
                    "SHOW_NUM_PRODUCTS" => "Y",
                    "SHOW_PERSONAL_LINK" => "N",
                    "SHOW_PRICE" => "Y",
                    "SHOW_PRODUCTS" => "N",
                    "SHOW_SUBSCRIBE" => "N",
                    "SHOW_SUMMARY" => "Y",
                    "SHOW_TOTAL_PRICE" => "Y"
                )
            );*/?>
        </div>
        <h2>Правый сайд бар-список</h2>
        <? $APPLICATION->IncludeComponent("maximaster:brands", ".default",
            array(),
            false
        ); ?>
    </div>
</section>
<footer>
    <a href="mailto:a.stankevich@maximaster.ru?subject=<?echo $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']?>">ФИО</a>
</footer>
    <script src="<?=SITE_TEMPLATE_PATH?>/scripts/zoomsl-3.0.min.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/scripts/script.js"></script>
</body>
</html>