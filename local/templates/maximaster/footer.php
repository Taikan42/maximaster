</div>
<div class="flex-block right-block">
    <div id="basket-container">
        <? $APPLICATION->IncludeComponent(
            "maximaster:basket.small",
            ".default",
            Array(
                "PATH_TO_BASKET" => "/local/templates/maximaster/cart/index.php",
                "PATH_TO_ORDER" => "/local/templates/maximaster/cart/order.php",
                "SHOW_NUM_PRODUCTS" => "Y",
                "SHOW_TOTAL_PRICE" => "Y"
            ));
        ?>
    </div>
    <h2>Правый сайд бар-список</h2>
    <? $APPLICATION->IncludeComponent(
        "maximaster:brands",
        ".default",
        array(
            "IBLOCK_ID" => "4"
        ));
    ?>
</div>
</section>
<footer>
    <a href="mailto:a.stankevich@maximaster.ru?subject=<? echo $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'] ?>">ФИО</a>
</footer>
<script src="<?= SITE_TEMPLATE_PATH ?>/scripts/zoomsl-3.0.min.js"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/scripts/script.js"></script>
</body>
</html>