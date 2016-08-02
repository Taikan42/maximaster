<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class MMBrands extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = trim($arParams['IBLOCK_ID']);
        if ($arParams['IBLOCK_ID'] == '')
            $arParams['IBLOCK_ID'] = '4';
        return $arParams;
    }

    public function executeComponent()
    {
        if (CModule::IncludeModule("iblock") and CModule::IncludeModule('highloadblock')) {
            $this->arResult['BRAND'] = $this->getBrands($this->arParams['IBLOCK_ID']);
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }

    private function getBrands($IBLOCK_ID)
    {
        $ELEMENT_ID = $_GET["ID"];
        $BRAND_XML = $_GET["BRAND"];
        $SECTION_ID = $_GET["SECTION_ID"];
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($IBLOCK_ID)->fetch();
        $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
        $hlDataClass = $hldata['NAME'] . 'Table';
        $arSelect = Array(
            "ID",
            "IBLOCK_ID",
            "PROPERTY_BRAND"
        );
        $arrXML_ID = array();
        if ($ELEMENT_ID or $BRAND_XML) {
            if ($ELEMENT_ID) {
                $arFilter = Array(
                    "ID" => $ELEMENT_ID
                );
                $res = CIBlockElement::GetList(
                    Array(),
                    $arFilter,
                    false,
                    false,
                    $arSelect
                );
                $res = $res->fetch();
                $arrXML_ID[] = $res["PROPERTY_BRAND_VALUE"];
            } else {
                $arrXML_ID[] = $BRAND_XML;
            }
        } else {
            if ($SECTION_ID) {
                $arFilter = Array(
                    "SECTION_ID" => $SECTION_ID,
                    "INCLUDE_SUBSECTIONS" => "Y"
                );
            } else {
                $arFilter = Array(
                    "IBLOCK_ID" => $IBLOCK_ID
                );
            }
            $res = CIBlockElement::GetList(
                Array(),
                $arFilter,
                false,
                false,
                $arSelect);
            while ($ob = $res->fetch()) {
                $brand = $ob["PROPERTY_BRAND_VALUE"];
                if (!in_array($brand, $arrXML_ID)) $arrXML_ID[] = $brand;
            }
        }
        $result = $hlDataClass::getList(array(
            'select' => array('UF_NAME', 'UF_XML_ID'),
            'filter' => array('UF_XML_ID' => $arrXML_ID),
        ));
        while ($res = $result->fetch()) {
            $arBrands["BRAND"][] = array(
                "NAME" => $res["UF_NAME"],
                "XML_ID" => $res["UF_XML_ID"]
            );
        }
        return $arBrands["BRAND"];
    }
}