<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if(CModule::IncludeModule("iblock")) {
    $idBLOCK = $_GET["ID"];
    $arSelect = Array(
        "ID",
        "IBLOCK_ID",
        "NAME",
        "DETAIL_TEXT",
        "DETAIL_PICTURE",
        "PROPERTY_COUNTRY",
        "PROPERTY_BRAND"
    );
    $arFilter = Array(
        "ID" => $idBLOCK
    );
    $res = CIBlockElement::GetList(
        Array(),
        $arFilter,
        false,
        false,
        $arSelect);
    $ob = $res->GetNextElement();
    $arFields = $ob->GetFields();
    $CPres = \CPrice::GetList([],[
            "PRODUCT_ID" => $arFields["ID"],
            "CATALOG_GROUP_ID" => 1]
    );
    $arr = $CPres->Fetch();
    $arrCprise = \CPrice::GetByID($arr["ID"]);
    $prise = $arrCprise["PRICE"];
    $currency = $arrCprise["CURRENCY"];
    $arResult = array(
        "ID" => $arFields["ID"],
        "NAME" => $arFields["NAME"],
        "TEXT" => $arFields["DETAIL_TEXT"],
        "PICTURE" => $arFields["DETAIL_PICTURE"],
        "PRICE" => $prise,
        "CURRENCY" => $currency,
        "NUMBER" => \CCatalogProduct::GetByID($arFields["ID"])["QUANTITY"],
        "COUNTRY" => $arFields["PROPERTY_COUNTRY_VALUE"]
    );
    $BrandID = $arFields["PROPERTY_BRAND_VALUE"];
    if (!CModule::IncludeModule('highloadblock'));
//сначала выбрать информацию о ней из базы данных
    $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(4)->fetch();
//затем инициализировать класс сущности
    $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $result = $hlDataClass::getList(array(
        'select' => array('UF_NAME'),
        'filter' => array('UF_XML_ID'=>$BrandID),
    ));
    $res = $result->fetch();
    $arResult["BREND"] = $res["UF_NAME"];
}
$this->IncludeComponentTemplate();