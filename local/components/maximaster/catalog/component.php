<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (CModule::IncludeModule("iblock")) {
    $idSECTION = $_GET["SECTION_ID"];
    $arResult["SECTION"] = array();
    $arResult["ELEMENT"] = array();
    if ($idSECTION) {
        $arResult["CHECK_SECTION"] = true;//Если выводятся элементы раздела- выводить описание раздела
        $res = CIBlockSection::getList(
            ["SORT" => "ASC"],
            ["ID" => $idSECTION],
            false,
            ["NAME", "DESCRIPTION", "PICTURE"],
            false
        );
        $res = $res->fetch();
        $arResult["SECTION"]["NAME"] = $res['NAME'];
        $arResult["SECTION"]["DESCRIPTION"] = $res['DESCRIPTION'];
        $arResult["SECTION"]["PICTURE"] = $res['PICTURE'];
        $arFilter = Array(
            "SECTION_ID" => $idSECTION,
            "INCLUDE_SUBSECTIONS" => "Y"
        );
    } else {
        $XMLBRAND = $_GET["BRAND"];
        $arResult["CHECK_SECTION"] = false; //не выводить описание раздела
        if ($XMLBRAND) {
            if (!CModule::IncludeModule('highloadblock')) ;
            $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($arParams["IBLOCK_ID"])->fetch();
            $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'] . 'Table';
            $result = $hlDataClass::getList(array(
                'select' => array('UF_NAME'),
                'filter' => array('UF_XML_ID' => $XMLBRAND),
            ));
            $res = $result->fetch();
            $arResult["SECTION"]["NAME"] = $res["UF_NAME"];
            $arFilter = Array(
                "PROPERTY_BRAND" => $XMLBRAND
            );
        } else {
            $res = CIBlock::getList(
                [],
                ["ID" => $arParams["IBLOCK_ID"]],
                false
            );
            $res = $res->fetch();
            $arResult["SECTION"]["NAME"] = $res["NAME"];
            $arFilter = Array(
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "INCLUDE_SUBSECTIONS" => "Y"
            );
        }
    }
    if ($GLOBALS["arrFilter"]) {
        $arFilter = array_merge($arFilter, $GLOBALS["arrFilter"]);
    }
    $arSelect = Array(
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "DETAIL_PAGE_URL",
        "NAME",
        "PREVIEW_TEXT",
        "PREVIEW_PICTURE"
    );
    $res = CIBlockElement::GetList(
        Array(),
        $arFilter,
        false,
        false,
        $arSelect);
    $elements_ID = array();
    while ($ob = $res->GetNext()) {
        $elements_ID[] = $ob["ID"];
        $arResult["ELEMENT"][$ob["ID"]] = array(
            "ID" => $ob["ID"],
            "DETAIL_PAGE_URL" => $ob["DETAIL_PAGE_URL"],
            "NAME" => $ob["NAME"],
            "PREVIEW_TEXT" => $ob["PREVIEW_TEXT"],
            "PREVIEW_PICTURE" => $ob["PREVIEW_PICTURE"]
        );
    }
    $res = \CPrice::GetList(
        [],
        ["PRODUCT_ID" => $elements_ID, "CATALOG_GROUP_ID" => 1],
        false,
        false,
        ["PRODUCT_ID", "PRICE", "CURRENCY"]
    );
    while ($ob = $res->GetNext()) {
        $arResult["ELEMENT"][$ob["PRODUCT_ID"]]["PRICE"] = $ob["PRICE"];
        $arResult["ELEMENT"][$ob["PRODUCT_ID"]]["CURRENCY"] = $ob["CURRENCY"];
    }
}
$this->IncludeComponentTemplate();
