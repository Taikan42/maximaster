<? require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");?>
<?
if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")) {
    if (isset($_POST['id'])&&isset($_POST['quantity'])&&isset($_POST['country'])&&isset($_POST['brand'])) {
        $PRODUCT_ID = intval($_POST['id']);
        $QUANTITY = intval($_POST['quantity']);
        $PROP = array(
            array(
                "NAME" => "Страна",
                "VALUE" => $_POST['country']
            ),
            array(
                "NAME" => "Бренд",
                "VALUE" => $_POST['brand']
            )
        );
        Add2BasketByProductID(
            $PRODUCT_ID,
            $QUANTITY,
            array(),
            $PROP
        );
    } else {
        echo "Нет параметров ";
    }
} else {
    echo "Не подключены модули";
}
?>