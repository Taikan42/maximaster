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
        array("ID", "NAME", "DESCRIPTION", "LOGOTIP", "PRICE")
    );
    while ($delob = $delres->Fetch()) {
        $ID = $delob["ID"];
        $arResult["DELIVERY"][$ID] = array(
            "ID" => $ID,
            /*"NAME" => $delob["NAME"],
            "DESCRIPTION" => $delob["DESCRIPTION"],*/
            "LOGOTIP" => CFile::ShowImage($delob["LOGOTIP"], 100, 100,"alt=\"".$delob["NAME"]."\"","",true,$delob["DESCRIPTION"]),
            "PRICE" => $delob["PRICE"]
        );
    }
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
            /*"NAME" => $payob["NAME"],
            "DESCRIPTION" => $payob["DESCRIPTION"],*/
            "LOGOTIP"=> CFile::ShowImage($payob["PSA_LOGOTIP"], 100, 100,"alt=\"".$payob["NAME"]."\"","",true,$payob["DESCRIPTION"])
        );
    }
}
$this->IncludeComponentTemplate();