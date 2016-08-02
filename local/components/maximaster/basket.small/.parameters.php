<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "PARAMETERS" => array(
        // BASE
        "PATH_TO_BASKET" => array(
            "NAME" => "Страница корзины",
            "TYPE" => "STRING",
            "DEFAULT" => '={SITE_DIR."personal/cart/"}',
            "PARENT" => "BASE",
        ),
        "PATH_TO_ORDER" => array(
            "NAME" => "Страница оформления заказа",
            "TYPE" => "STRING",
            "DEFAULT" => '={SITE_DIR."personal/order/make/"}',
            "PARENT" => "BASE",
        ),
        "SHOW_NUM_PRODUCTS" => array(
            "NAME" => "Показывать количество товара",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "PARENT" => "BASE",
        ),
        "SHOW_TOTAL_PRICE" => array(
            "NAME" => "Показывать общую сумму по товарам",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "PARENT" => "BASE",
        ),
    )
);