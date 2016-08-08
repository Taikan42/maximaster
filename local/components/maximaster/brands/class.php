<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class MMBrands extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = trim($arParams['IBLOCK_ID']);
        if ($arParams['IBLOCK_ID'] == '')
            $arParams['IBLOCK_ID'] = IBLOCK_CATALOG;
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
        //Обработка параметра SECTION_CODE_PATH
        $Params_URL = explode('/', $_GET["SECTION_CODE_PATH"]);
        $Params = $Params_URL[count($Params_URL) - 1];
        $arParams = explode('?', $Params);
        $SECTION_CODE = $arParams[0];
        //
        $ELEMENT_ID = $_GET["ID"];
        $BRAND_XML = $_GET["BRAND"];
        //информация из базы данных
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($IBLOCK_ID)->fetch();
        //инициализация класса сущности !Не удалять!
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
            if ($SECTION_CODE) {
                $arFilter = Array(
                    "SECTION_CODE" => $SECTION_CODE,
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
                $arSelect
            );
            while ($ob = $res->fetch()) {
                $brand = $ob["PROPERTY_BRAND_VALUE"];
                if ( ! in_array($brand, $arrXML_ID)) {
                    $arrXML_ID[] = $brand;
                }
            }
        }
        
        $result = $hlDataClass::GetList(array(
            'select' => array('UF_NAME', 'UF_XML_ID'),
            'filter' => array('UF_XML_ID' => $arrXML_ID),
        ));
        while ($res = $result->fetch()) {
            $arBrands["BRAND"][] = array(
                "NAME" => $res["UF_NAME"],
                "XML_ID" => $res["UF_XML_ID"]
            );
        }
        $arBrands["URL"] = $_SERVER['REQUEST_URI'];
        return $arBrands["BRAND"];
    }
}