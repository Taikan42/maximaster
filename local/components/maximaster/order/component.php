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
            "NAME" => $delob["NAME"],
            "DESCRIPTION" => $delob["DESCRIPTION"],
            "LOGOTIP" => CFile::ShowImage($delob["LOGOTIP"], 100, 100),
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
        array("ID", "NAME", "DESCRIPTION")
    );
    $payID = array();
    while ($payob = $payres->Fetch()) {
        $ID = $payob["ID"];
        $payID[] = $ID;
        $arResult["PAYMENT"][$ID] = array(
            "ID" => $payob["ID"],
            "NAME" => $payob["NAME"],
            "DESCRIPTION" => $payob["DESCRIPTION"]
        );
    }
    $payres = CSalePaySystemAction::GetList(
        array(
            "SORT" => "ASC",
        ),
        array(
            "ID" => $payID
        ),
        false,
        false,
        array("ID", "LOGOTIP")
    );
    while ($payob = $payres->Fetch()) {
        $ID = $payob["ID"];
        $arResult["PAYMENT"][$ID]["LOGOTIP"] = CFile::ShowImage($payob["LOGOTIP"], 100, 100);
    }
}
$this->IncludeComponentTemplate();