<? namespace Maximaster;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class Catalog extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = trim($arParams['IBLOCK_ID']);
        if ($arParams['IBLOCK_ID'] == '')
            $arParams['IBLOCK_ID'] = IBLOCK_CATALOG;

        $arParams['BASKET_PAGE'] = trim($arParams['BASKET_PAGE']);
        if ($arParams['BASKET_PAGE'] == '')
            $arParams['BASKET_PAGE'] = '/local/templates/maximaster/cart/index.php';

        $arParams['SECTIONS'] = trim($arParams['SECTIONS']);
        if ($arParams['SECTIONS'] != 'Y')
            $arParams['SECTIONS'] = 'N';
        return $arParams;
    }
    
    public function executeComponent()
    {
        $this->arResult = $this->getElements($this->arParams['IBLOCK_ID'], $this->arParams['SECTIONS']);
        $this->arResult['BASKET_PAGE'] = $this->arParams['BASKET_PAGE'];
        $this->includeComponentTemplate();
        return $this->arResult;
    }
    
    private function getElements($IBLOCK_ID, $SECTIONS)
    {
        $SECTION_ID = intval($_GET["SECTION_ID"]);
        $BRAND_XML = $_GET["BRAND"];
        $arFilter = null;
        $arElements = [];
        if ($SECTIONS == "Y") {
            /*Страница разделов*/
            $arElements["TITLE"] = "Разделы:";
            $arElements["SECTION"] = array();
            $res = \CIBlockSection::GetList(
                ["NAME" => "ASC"],
                [
                    "IBLOCK_ID" => $IBLOCK_ID,
                    "ACTIVE" => "Y",
                    "DEPTH_LEVEL" => 1
                ],
                false,
                ["NAME", "DESCRIPTION", "SECTION_PAGE_URL", "PICTURE"],
                false
            );
            while ($ob = $res->GetNext()) {
                $arElements["SECTION"][] = array(
                    "NAME" => $ob['NAME'],
                    "DESCRIPTION" => $ob['DESCRIPTION'],
                    "SECTION_PAGE_URL" => $ob['SECTION_PAGE_URL'],
                    "PICTURE" => $ob['PICTURE']
                );
            }
        } elseif ($SECTION_ID) {
            /*Страница раздела*/
            $arElements["SECTION"] = array();
            $res = \CIBlockSection::GetList(
                ["SORT" => "ASC"],
                ["ID" => $SECTION_ID],
                false,
                ["NAME", "DESCRIPTION", "SECTION_PAGE_URL", "PICTURE"],
                false
            );
            $res = $res->GetNext();
            $arElements["TITLE"] = $res['NAME'];
            $arElements["SECTION"][] = array(
                "NAME" => $res['NAME'],
                "DESCRIPTION" => $res['DESCRIPTION'],
                "SECTION_PAGE_URL" => $res['SECTION_PAGE_URL'],
                "PICTURE" => $res['PICTURE']
            );
            $arFilter = Array(
                "SECTION_ID" => $SECTION_ID,
                "INCLUDE_SUBSECTIONS" => "Y"
            );
        } elseif ($BRAND_XML) {
            /*Страница фильтра по бренду*/
            if (\CModule::IncludeModule('highloadblock')) {
                $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById($IBLOCK_ID)->fetch();
                $hlDataClass = $hldata['NAME'] . 'Table';
                $result = $hlDataClass::GetList(array(
                    'select' => array('UF_NAME'),
                    'filter' => array('UF_XML_ID' => $BRAND_XML),
                ));
                $res = $result->Fetch();
                $arElements["TITLE"] = $res["UF_NAME"];
                $arFilter = Array(
                    "PROPERTY_BRAND" => $BRAND_XML
                );
            }
        } else {
            /*Главная страница*/
            $res = \CIBlock::GetList(
                [],
                ["ID" => $IBLOCK_ID],
                false
            );
            $res = $res->Fetch();
            $arElements["TITLE"] = $res["NAME"];
            $arFilter = Array(
                "IBLOCK_ID" => $IBLOCK_ID,
                "INCLUDE_SUBSECTIONS" => "Y"
            );
        }


        if ($arFilter) {
            $arElements["ELEMENT"] = array();
            if ($GLOBALS["arrFilter"]) {
                $arFilter = array_merge($arFilter, $GLOBALS["arrFilter"]);
            }
            $arSelect = array(
                "ID",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "DETAIL_PAGE_URL",
                "NAME",
                "PREVIEW_TEXT",
                "PREVIEW_PICTURE"
            );
            $res = \CIBlockElement::GetList(
                array(),
                $arFilter,
                false,
                false,
                $arSelect
            );
            $elements_ID = array();
            while ($ob = $res->GetNext()) {//GetNext для коректного вывода DETAIL_PAGE_URL
                $elements_ID[] = $ob["ID"];
                $arElements["ELEMENT"][$ob["ID"]] = array(
                    "ID" => $ob["ID"],
                    "DETAIL_PAGE_URL" => $ob["DETAIL_PAGE_URL"],
                    "NAME" => $ob["NAME"],
                    "PREVIEW_TEXT" => $ob["PREVIEW_TEXT"],
                    "PREVIEW_PICTURE" => $ob["PREVIEW_PICTURE"],
                );
            }
            if ($elements_ID) {
                $res = \CPrice::GetList(
                    [],
                    ["PRODUCT_ID" => $elements_ID, "CATALOG_GROUP_ID" => BASE_PRICE],
                    false,
                    false,
                    ["PRODUCT_ID", "PRICE", "CURRENCY"]
                );
                while ($ob = $res->Fetch()) {
                    $arElements["ELEMENT"][$ob["PRODUCT_ID"]]["PRICE"] = $ob["PRICE"];
                    $arElements["ELEMENT"][$ob["PRODUCT_ID"]]["CURRENCY"] = $ob["CURRENCY"];
                }
            }
        }
        return $arElements;
    }
}