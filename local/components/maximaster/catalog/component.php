<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (CModule::IncludeModule("iblock")) {
    $idSECTION = $_GET["SECTION_ID"];
    $arResult["SECTION"] = array();
    $arResult["ELEMENT"] = array();
    if ($idSECTION) {
        $res = CIBlockSection::GetByID($idSECTION);
        if ($ar_res = $res->GetNext()) {
            $arResult["CHECK_SECTION"] = true;
            $arResult["SECTION"]["NAME"] = $ar_res['NAME'];
            $arResult["SECTION"]["DESCRIPTION"] = $ar_res['DESCRIPTION'];
            $arResult["SECTION"]["PICTURE"] = $ar_res['PICTURE'];
        }
        $arFilter = Array(
            "SECTION_ID" => $idSECTION,
            "INCLUDE_SUBSECTIONS" => "Y"
        );
    } else {
        $XMLBRAND = $_GET["BRAND"];
        $arResult["CHECK_SECTION"] = false;
        if ($XMLBRAND){
            if (!CModule::IncludeModule('highloadblock'));
            $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(4)->fetch();
            $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'].'Table';
            $result = $hlDataClass::getList(array(
                'select' => array('UF_NAME'),
                'filter' => array('UF_XML_ID'=>$XMLBRAND),
            ));
            $res = $result->fetch();
            $arResult["SECTION"]["NAME"] = $res["UF_NAME"];
            $arFilter = Array(
                "PROPERTY_BRAND" => $XMLBRAND
            );
        } else {
            $arResult["SECTION"]["NAME"] = CIBlock::GetByID(4)->GetNext()["NAME"];
            $arFilter = Array(
                "IBLOCK_ID" => 4,
                "INCLUDE_SUBSECTIONS" => "Y"
            );
        }
    }
    if($GLOBALS["arrFilter"]){
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
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $CPres = \CPrice::GetList([], [
                "PRODUCT_ID" => $arFields["ID"],
                "CATALOG_GROUP_ID" => 1]
        );
        $arr = $CPres->Fetch();
        $arrCprise = \CPrice::GetByID($arr["ID"]);
        $prise = $arrCprise["PRICE"];
        $currency = $arrCprise["CURRENCY"];
        $arResult["ELEMENT"][] = array(
            "DETAIL_PAGE_URL" => $arFields["DETAIL_PAGE_URL"],
            "NAME" => $arFields["NAME"],
            "PREVIEW_TEXT" => $arFields["PREVIEW_TEXT"],
            "PREVIEW_PICTURE" => $arFields["PREVIEW_PICTURE"],
            "PRICE" => $prise,
            "CURRENCY" => $currency
        );
    }
}
$this->IncludeComponentTemplate();
