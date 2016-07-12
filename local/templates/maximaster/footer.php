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
    <a href="#">ФИО</a>
</footer>
<script src="<?=SITE_TEMPLATE_PATH?>/apps/menu-sticked.js"></script>
</body>
</html>