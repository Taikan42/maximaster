<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/local/class/IblockCSV.php");
echo 'start<br>';
try{
    $IBlock_CSV = new \Maximaster\IblockCSV($_SERVER['DOCUMENT_ROOT']."/local/Shop.csv");
    $IBlock_CSV->Import();
} catch (Exception $e){
    echo "Ошибка: " . $e->getMessage();
}