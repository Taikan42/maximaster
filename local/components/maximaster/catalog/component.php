<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*$Select = Array("IBLOCK_SECTION_ID", "NAME");
$arFilter = Array("IBLOCK_ID" => 4);
$res = CIBlockSection::GetList(
    Array(),
    $arFilter,
    false,
    $Select,
    false
);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    print_r($arFields);
}
$arResult['DATE'] = date($arParams["TEMPLATE_FOR_DATE"]);*/
$this->IncludeComponentTemplate();
