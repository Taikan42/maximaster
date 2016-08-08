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
        //Обработка параметра SECTION_CODE_PATH
        $Params_URL = explode('/', $_GET["SECTION_CODE_PATH"]);
        $Params = $Params_URL[count($Params_URL) - 1];
        $arParams = explode('?', $Params);
        $SECTION_CODE = $arParams[0];
        //
        $BRAND_XML = $_GET["BRAND"];
        $arElements = array();
        $FilterFoElements = array();
        $FilterFoSection = array();
        $SelectFoSection = array();
        $OrderFoSection = array();
        if ($SECTIONS == "Y") {
            /*Страница разделов*/
            $arElements["TITLE"] = "Разделы:";
            $OrderFoSection["NAME"] = "ASC";
            $FilterFoSection = array(
                "IBLOCK_ID" => $IBLOCK_ID,
                "ACTIVE" => "Y",
                "DEPTH_LEVEL" => 1
            );
            $SelectFoSection = array(
                "NAME", "DESCRIPTION", "SECTION_PAGE_URL", "PICTURE"
            );
        } elseif ($SECTION_CODE) {
            /*Страница раздела*/
            $OrderFoSection["SORT"] = "ASC";
            $FilterFoSection = array(
                "CODE" => $SECTION_CODE
            );
            $SelectFoSection = array(
                "NAME", "DESCRIPTION", "SECTION_PAGE_URL", "PICTURE"
            );
            $FilterFoElements = Array(
                "SECTION_CODE" => $SECTION_CODE,
                "INCLUDE_SUBSECTIONS" => "Y"
            );
        } else {
            /*Главная страница*/
            $res = \CIBlock::GetList(
                array(),
                array("ID" => $IBLOCK_ID),
                false
            );
            $res = $res->Fetch();
            $arElements["TITLE"] = $res["NAME"];
            $FilterFoElements = array(
                "IBLOCK_ID" => $IBLOCK_ID,
                "INCLUDE_SUBSECTIONS" => "Y"
            );
        }

        if (!empty($FilterFoSection)) {
            $arElements["SECTION"] = array();
            $res = \CIBlockSection::GetList(
                $OrderFoSection,
                $FilterFoSection,
                false,
                $SelectFoSection,
                false
            );
            while ($ob = $res->GetNext()) {
                $arElements["SECTION"][] = array(
                    "NAME" => $ob['NAME'],
                    "DESCRIPTION" => $ob['DESCRIPTION'],
                    "SECTION_PAGE_URL" => $ob['SECTION_PAGE_URL'],
                    "PICTURE" => $ob['PICTURE']
                );
                if (!empty($arElements["TITLE"])) {
                    $arElements["TITLE"] = $ob["NAME"];
                }
            }
        }

        if (!empty($FilterFoElements)) {
            $arElements["ELEMENT"] = array();
            if ($GLOBALS["arrFilter"]) {
                $FilterFoElements = array_merge($FilterFoElements, $GLOBALS["arrFilter"]);
            }
            if ($BRAND_XML) {
                $FilterFoElements["PROPERTY_BRAND"] = $BRAND_XML;
            }
            $res = \CIBlockElement::GetList(
                array(),
                $FilterFoElements,
                false,
                false,
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "DETAIL_PAGE_URL",
                    "NAME",
                    "PREVIEW_TEXT",
                    "PREVIEW_PICTURE"
                )
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
                    array(),
                    array(
                        "PRODUCT_ID" => $elements_ID,
                        "CATALOG_GROUP_ID" => BASE_PRICE
                    ),
                    false,
                    false,
                    array("PRODUCT_ID", "PRICE", "CURRENCY")
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