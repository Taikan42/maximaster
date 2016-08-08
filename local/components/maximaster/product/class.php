<?
namespace Maximaster;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class Product extends \CBitrixComponent
{
    public function executeComponent()
    {
        if (\CModule::IncludeModule("iblock")) 
        {
            $this->arResult = $this->getElement();
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }
    
    private function getElement()
    {
        $ELEMENT_ID = $_GET["ID"];
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
            "ID" => $ELEMENT_ID
        );
        $ob = \CIBlockElement::GetList(
            array(),
            $arFilter,
            false,
            false,
            $arSelect
        )->Fetch();

        $CPres = \CPrice::GetList(
            [],
            [
                "PRODUCT_ID" => $ob["ID"],
                "CATALOG_GROUP_ID" => BASE_PRICE
            ]
        );
        $arr = $CPres->Fetch();
        $arrCprise = \CPrice::GetByID($arr["ID"]);
        $prise = $arrCprise["PRICE"];
        $currency = $arrCprise["CURRENCY"];
        $arElement = array(
            "ID" => $ob["ID"],
            "NAME" => $ob["NAME"],
            "TEXT" => $ob["DETAIL_TEXT"],
            "PICTURE" => $ob["DETAIL_PICTURE"],
            "PRICE" => $prise,
            "CURRENCY" => $currency,
            "NUMBER" => \CCatalogProduct::GetByID($ob["ID"])["QUANTITY"],
            "COUNTRY" => $ob["PROPERTY_COUNTRY_VALUE"]
        );
        $BrandID = $ob["PROPERTY_BRAND_VALUE"];
        if ( ! \CModule::IncludeModule('highloadblock'));
        $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById(4)->fetch();

        $hlDataClass = $hldata['NAME'].'Table';
        $result = $hlDataClass::GetList(array(
            'select' => array('UF_NAME'),
            'filter' => array('UF_XML_ID'=>$BrandID),
        ));
        $res = $result->Fetch();
        $arElement["BRAND"] = $res["UF_NAME"];
        return $arElement;
    }
}