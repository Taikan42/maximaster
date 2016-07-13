<? require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");?>
<?
if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")) {
    if (isset($_POST['id'])&&isset($_POST['quantity'])) {
        $PRODUCT_ID = intval($_POST['id']);
        $QUANTITY = intval($_POST['quantity']);
        Add2BasketByProductID( $PRODUCT_ID, $QUANTITY );
    } else {
        echo "Нет параметров ";
    }
} else {
    echo "Не подключены модули";
}
?>