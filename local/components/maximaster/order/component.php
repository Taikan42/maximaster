<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arResult["ACTION_URL"] = $arParams["URL_ORDER_SUBMIT"];
if (CModule::IncludeModule("sale")) {
    $arResult["DELIVERY"] = array();
    $arResult["PAYMENT"] = array();
    $delres = CSaleDelivery::GetList(
        array(
            "SORT" => "ASC",
            "NAME" => "ASC"
        ),
        array(
            "LID" => SITE_ID,
            "ACTIVE" => "Y"
        ),
        false,
        false,
        array("ID", "NAME", "DESCRIPTION", "LOGOTIP")
    );
    $delID = array();
    while ($delob = $delres->Fetch()) {
        $ID = $delob["ID"];
        $delID[] = $ID;
        $arResult["DELIVERY"][$ID] = array(
            "ID" => $ID,
            /*"NAME" => $delob["NAME"],
            "DESCRIPTION" => $delob["DESCRIPTION"],*/
            "LOGOTIP" => CFile::ShowImage($delob["LOGOTIP"], 100, 100, "alt=\"" . $delob["NAME"] . "\"", "")
        );
    }
    $delres = CSaleDelivery2PaySystem::GetList(
        array(
            "DELIVERY_ID" => $delID
        )
    );
    while ($delob = $delres->Fetch()) {
        $ID = $delob["DELIVERY_ID"];
        if (in_array($ID, $delID)) {
            $arResult["DELIVERY"][$ID]["PAYMENT_ID"][$delob["PAYSYSTEM_ID"]] = $delob["PAYSYSTEM_ID"];
        }
    };
    $payres = CSalePaySystem::GetList(
        array(
            "SORT" => "ASC",
        ),
        array(
            "LID" => SITE_ID,
            "ACTIVE" => "Y"
        ),
        false,
        false,
        array("ID", "NAME", "DESCRIPTION", "PSA_LOGOTIP")
    );
    while ($payob = $payres->Fetch()) {
        $ID = $payob["ID"];
        $arResult["PAYMENT"][$ID] = array(
            "ID" => $payob["ID"],
            "NAME" => $payob["NAME"],
            "DESCRIPTION" => $payob["DESCRIPTION"],
            "LOGOTIP" => CFile::ShowImage($payob["PSA_LOGOTIP"], 100, 100, "alt=\"" . $payob["NAME"] . "\"", "")
        );
    };
}
$this->IncludeComponentTemplate();