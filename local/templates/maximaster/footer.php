    </div>
    <div class="flex-block right-block">
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