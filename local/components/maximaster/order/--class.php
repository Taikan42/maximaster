<? namespace Maximaster;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class FormOrder extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['URL_ORDER_SUBMIT'] = trim($arParams['URL_ORDER_SUBMIT']);
        if ($arParams['URL_ORDER_SUBMIT'] == '')
            $arParams['URL_ORDER_SUBMIT'] = '/';

        return $arParams;
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
        return $this->arResult;
    }
}