<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (CModule::IncludeModule("iblock")) {
    if (!CModule::IncludeModule('highloadblock'));
    $idBRAND = $_GET["ID"];
    $XMLBRAND = $_GET["BRAND"];
    $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(4)->fetch();
    $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $arSelect = Array(
        "ID",
        "IBLOCK_ID",
        "PROPERTY_BRAND"
    );
    if ($idBRAND or $XMLBRAND){
        $arResult["MULTIPLE"] = false;
        if ($idBRAND){
            $arFilter = Array(
                "ID" => $idBRAND
            );
            $res = CIBlockElement::GetList(
                Array(),
                $arFilter,
                false,
                false,
                $arSelect
            );
            $arFields = $res->GetNext();
            $arResult["XML_ID"] = $arFields["PROPERTY_BRAND_VALUE"];
        } else {
            $arResult["XML_ID"] = $XMLBRAND;
        }
        $result = $hlDataClass::getList(array(
            'select' => array('UF_NAME'),
            'filter' => array('UF_XML_ID'=>$arResult["XML_ID"]),
        ));
        $res = $result->fetch();
        $arResult["BRAND"] = $res["UF_NAME"];
    } else {
        $arResult["MULTIPLE"] = true;
        $idSECTION = $_GET["SECTION_ID"];
        $arResult["BRAND"] = array();
        if ($idSECTION) {
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
            $arrXMLID = array();
            while($ar_fields = $res->GetNext())
            {
                $brand = $ar_fields["PROPERTY_BRAND_VALUE"];
                if(!in_array($brand,$arrXMLID)) $arrXMLID[] = $brand;
            }
            $result = $hlDataClass::getList(array(
                'select' => array('UF_NAME','UF_XML_ID'),
                'filter' => array('UF_XML_ID'=>$arrXMLID),
            ));
            while($res = $result->fetch())
            {
                $arResult["BRAND"][] = array(
                    "NAME" => $res["UF_NAME"],
                    "XML_ID" => $res["UF_XML_ID"]
                );
            }
        } else {
            $arFilter = Array(
                "IBLOCK_ID" => 4
            );
            $res = CIBlockElement::GetList(
                Array(),
                $arFilter,
                false,
                false,
                $arSelect
            );
            $arrXMLID = array();
            while($ar_fields = $res->GetNext())
            {
                $brand = $ar_fields["PROPERTY_BRAND_VALUE"];
                if(!in_array($brand,$arrXMLID)) $arrXMLID[] = $brand;
            }
            $result = $hlDataClass::getList(array(
                'select' => array('UF_NAME','UF_XML_ID'),
                'filter' => array('UF_XML_ID'=>$arrXMLID),
            ));
            while($res = $result->fetch())
            {
                $arResult["BRAND"][] = array(
                    "NAME" => $res["UF_NAME"],
                    "XML_ID" => $res["UF_XML_ID"]
                );
            }
        }
    }
}
$this->IncludeComponentTemplate();