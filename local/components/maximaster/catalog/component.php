<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if(CModule::IncludeModule("iblock")) {
    $idSECTION = $_GET["SECTION_ID"];
    $arResult["SECTION"] = array();
    $arResult["ELEMENT"] = array();
    $res = CIBlockSection::GetByID($idSECTION);
    if ($ar_res = $res->GetNext()) {
        $arResult["SECTION"]["NAME"] = $ar_res['NAME'];
        $arResult["SECTION"]["DESCRIPTION"] = $ar_res['DESCRIPTION'];
        $arResult["SECTION"]["PICTURE"] = $ar_res['PICTURE'];
    }
    $arSelect = Array(
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "DETAIL_PAGE_URL",
        "NAME",
        "PREVIEW_TEXT",
        "PREVIEW_PICTURE",
        "PROPERTY_PRICE",
    );//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
    $arFilter = Array(
        "SECTION_ID" => $idSECTION,
        "INCLUDE_SUBSECTIONS" => "Y"
    );
    $res = CIBlockElement::GetList(
        Array(),
        $arFilter,
        false,
        false,
        $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arResult["ELEMENT"][] = array(
            "DETAIL_PAGE_URL" => $arFields["DETAIL_PAGE_URL"],
            "NAME" => $arFields["NAME"],
            "PREVIEW_TEXT" => $arFields["PREVIEW_TEXT"],
            "PREVIEW_PICTURE" => $arFields["PREVIEW_PICTURE"],
            "PRICE" => $arFields["PROPERTY_PRICE_VALUE"],
        );
    }
}
$this->IncludeComponentTemplate();