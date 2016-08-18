<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php");
$type = $_POST["type"];
if($type == "PaySys"){
    $DeliveryID = $_POST["id"];
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
    $arResult["PAY_SYSTEM"] = array();
    while ($Payob = $Payres->Fetch()) {
        $ID = $Payob["ID"];
        $arResult["PAY_SYSTEM"][$ID] = array(
            "ID" => $ID,
            "NAME" => $Payob["NAME"],
            "DESCRIPTION" => $Payob["DESCRIPTION"],
            "LOGOTIP" => CFile::ShowImage($Payob["PSA_LOGOTIP"], 100, 100, "alt=\"" . $Payob["NAME"] . "\"", "")
        );
    };
    require(realpath(dirname(__FILE__)).'/templates/.default/ajax_payment.php');
} elseif ($type == "Cost"){
    $idDelivery = $_POST["idDelivery"];
    $location = $_POST["location"];
    $APPLICATION->IncludeComponent(
        "maximaster:delivery.calculator",
        ".default",
        Array(
            "DELIVERY_ID" => $idDelivery,
            "LOCATION_ID" => $location
        )
    );
}
