<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php");
$PostID = $_POST["id"];
$Post = explode('-', $PostID);
$DeliveryID = $Post[1];
$delres = CSaleDelivery2PaySystem::GetList(
    array(
        "DELIVERY_ID" => $DeliveryID
    )
);
$PaySysID = array();
while ($delob = $delres->Fetch()) {
    if ($DeliveryID ==$delob["DELIVERY_ID"]){
        if (!in_array($delob["PAYSYSTEM_ID"], $PaySysID)){
            $PaySysID[] = $delob["PAYSYSTEM_ID"];
        }
    }
};
$Payres = CSalePaySystem::GetList(
    array(
        "ID" => "ASC",
    ),
    array(
        "ID" => $PaySysID,
        "ACTIVE" => "Y"
    ),
    false,
    false,
    array("ID", "NAME", "DESCRIPTION", "PSA_LOGOTIP")
);
$PaySys = array();
while ($Payob = $Payres->Fetch()) {
    $ID = $Payob["ID"];
    $PaySys[$ID] = array(
        "ID" => $ID,
        "NAME" => $Payob["NAME"],
        "DESCRIPTION" => $Payob["DESCRIPTION"],
        "LOGOTIP" => CFile::ShowImage($Payob["PSA_LOGOTIP"], 100, 100, "alt=\"" . $Payob["NAME"] . "\"", "")
    );
};
foreach ($PaySys as $arItem){
    echo ("<div class=radio_wrap payment_wrap paymentID-".$arItem["ID"].">");
        echo ("<input type='radio' name='Payment' id=pay-".$arItem["ID"]." value=".$arItem["ID"]."/>");
        echo ("<label for=pay-".$arItem["ID"].">");
            echo ($arItem["LOGOTIP"]);
        echo ("</label>");
    echo ("</div>");
}