<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (CModule::IncludeModule("iblock")) {
    $arResult["BASKET_PAGE"] = $arParams["BASKET_PAGE"];
    $SECTIONS = $arParams["SECTIONS"];
    $SECTION_ID = intval($_GET["SECTION_ID"]);
    $BRAND_XML = $_GET["BRAND"];
    $arFilter = null;
    if ($SECTIONS === "Y") {
        /*Страница разделов*/
        $arResult["TITLE"] = "Разделы:";
        $arResult["SECTION"] = array();
        $res = CIBlockSection::getList(
            ["SORT" => "ASC"],
            array(
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
                "DEPTH_LEVEL" => 1
            ),
            false,
            ["NAME", "DESCRIPTION", "SECTION_PAGE_URL", "PICTURE"],
            false
        );
        while ($ob = $res->GetNext()) {
            $arResult["SECTION"][] = array(
                "NAME" => $ob['NAME'],
                "DESCRIPTION" => $ob['DESCRIPTION'],
                "SECTION_PAGE_URL" => $ob['SECTION_PAGE_URL'],
                "PICTURE" => $ob['PICTURE']
            );
        }
    } elseif ($SECTION_ID) {
        /*Страница раздела*/
        $arResult["SECTION"] = array();
        $res = CIBlockSection::getList(
            ["SORT" => "ASC"],
            ["ID" => $SECTION_ID],
            false,
            ["NAME", "DESCRIPTION", "SECTION_PAGE_URL", "PICTURE"],
            false
        );
        $res = $res->GetNext();
        $arResult["TITLE"] = $res['NAME'];
        $arResult["SECTION"][] = array(
            "NAME" => $res['NAME'],
            "DESCRIPTION" => $res['DESCRIPTION'],
            "SECTION_PAGE_URL" => $ob['SECTION_PAGE_URL'],
            "PICTURE" => $res['PICTURE']
        );
        $arFilter = Array(
            "SECTION_ID" => $SECTION_ID,
            "INCLUDE_SUBSECTIONS" => "Y"
        );
    } elseif ($BRAND_XML) {
        /*Страница фильтра по бренду*/
        if (CModule::IncludeModule('highloadblock')) {
            $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($arParams["IBLOCK_ID"])->fetch();
            $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'] . 'Table';
            $result = $hlDataClass::getList(array(
                'select' => array('UF_NAME'),
                'filter' => array('UF_XML_ID' => $BRAND_XML),
            ));
            $res = $result->fetch();
            $arResult["TITLE"] = $res["UF_NAME"];
            $arFilter = Array(
                "PROPERTY_BRAND" => $BRAND_XML
            );
        }
    } else {
        /*Главная страница*/
        $res = CIBlock::getList(
            [],
            ["ID" => $arParams["IBLOCK_ID"]],
            false
        );
        $res = $res->fetch();
        $arResult["TITLE"] = $res["NAME"];
        $arFilter = Array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "INCLUDE_SUBSECTIONS" => "Y"
        );
    }

    if ($arFilter) {
        $arResult["ELEMENT"] = array();
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
        while ($ob = $res->GetNext()) {//GetNext для коректного вывода DETAIL_PAGE_URL
            $elements_ID[] = $ob["ID"];
            $arResult["ELEMENT"][$ob["ID"]] = array(
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
                ["PRODUCT_ID" => $elements_ID, "CATALOG_GROUP_ID" => 1],
                false,
                false,
                ["PRODUCT_ID", "PRICE", "CURRENCY"]
            );
            while ($ob = $res->fetch()) {
                $arResult["ELEMENT"][$ob["PRODUCT_ID"]]["PRICE"] = $ob["PRICE"];
                $arResult["ELEMENT"][$ob["PRODUCT_ID"]]["CURRENCY"] = $ob["CURRENCY"];
            }
        }
    }
}
$this->IncludeComponentTemplate();
