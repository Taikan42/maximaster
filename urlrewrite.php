<?
$arUrlRewrite = array(
    array(
        "CONDITION" => "#^/bitrix/services/ymarket/#",
        "RULE" => "",
        "ID" => "",
        "PATH" => "/bitrix/services/ymarket/index.php",
    ),
    array(
        "CONDITION" => "#^/personal/order/#",
        "RULE" => "",
        "ID" => "bitrix:sale.personal.order",
        "PATH" => "/personal/order/index.php",
    ),
    array(
        "CONDITION" => "#^/store/#",
        "RULE" => "",
        "ID" => "bitrix:catalog.store",
        "PATH" => "/store/index.php",
    ),
    array(
        "CONDITION" => "#^/news/#",
        "RULE" => "",
        "ID" => "bitrix:news",
        "PATH" => "/news/index.php",
    ),
    array(
        "CONDITION" => "#^/catalog/(.+)#",
        "RULE" => "SECTION_CODE_PATH=$1",
        "PATH" => "/catalog/list.php",
    ),
    array(
        "CONDITION" => "#^/catalog.*/product/([0-9]+).*#",
        "RULE" => "ID=$1",
        "PATH" => "/catalog/product.php",
    ),
    array(
        "CONDITION" => "#^/cart#",
        "RULE" => "",
        "PATH" => "/local/templates/maximaster/cart/index.php",
    ),
);
?>