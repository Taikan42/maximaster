<?
CModule::IncludeModule("sale");

class MTulaExpress
{
    function Init()
    {
        return array(
            "SID" => "tulaexpress",
            "NAME" => "Тула экспресс",
            "DESCRIPTION" => "",
            "DESCRIPTION_INNER" => "",
            "BASE_CURRENCY" => COption::GetOptionString("sale", "default_currency", "RUB"),
            "HANDLER" => __FILE__,
            "DBGETSETTINGS" => array("MTulaExpress", "GetSettings"),
            "DBSETSETTINGS" => array("MTulaExpress", "SetSettings"),
            "GETCONFIG" => array("MTulaExpress", "GetConfig"),
            "COMPABILITY" => array("MTulaExpress", "Compability"),
            "CALCULATOR" => array("MTulaExpress", "Calculate"),

            "PROFILES" => array(
                "base" => array(
                    "TITLE" => "доставка",
                    "DESCRIPTION" => "",
                    "RESTRICTIONS_WEIGHT" => array(0),
                    "RESTRICTIONS_SUM" => array(0),
                )
            )
        );
    }

    function GetConfig()
    {
        $arConfig = array(
            "CONFIG_GROUPS" => array(
                "prise" => "Стоимость доставки",
            ),
            "CONFIG" => array(
                "tula1" => array(
                    "TITLE" => "Доставка по туле в первой половине дня",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "prise"),
                "tula2" => array(
                    "TITLE" => "Доставка по туле во второй половине дня",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "prise"),
                "moscow" => array(
                    "TITLE" => "Наценка при доставке в Москву в %",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "prise"),
                "nodeliver" => array(
                    "TITLE" => "Не доставлять в города на букву:",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "prise"),
                "others" => array(
                    "TITLE" => "Цена для остальных случаев",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "prise"),
            )
        );
        return $arConfig;
    }

    function SetSettings($arSettings)
    {
        foreach ($arSettings as $key => $value) {
            if (strlen($value) > 0)
                $arSettings[$key] = doubleval($value);
            else
                unset($arSettings[$key]);
        }
        return serialize($arSettings);
    }

    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    function Compability($arOrder, $arConfig)
    {
        $res_loc = CSaleLocation::GetList(
            array(),
            array("ID" => $arOrder["LOCATION_TO"]),
            false,
            false,
            array()
        );
        if ($ob = $res_loc->Fetch()) //Проверка наличия местоположения
            if ($ob["CITY_NAME"]{0} == $arConfig["nodeliver"]["VALUE"])
                return array();
            else
                return array('base');
        else
            return array();
    }

    function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        if ($profile == 'base') {
            $resultprice = 0;
            $res_loc = CSaleLocation::GetList(
                array(),
                array("ID" => $arOrder["LOCATION_TO"]),
                false,
                false,
                array()
            );
            $cityname = $res_loc->Fetch()["CITY_NAME"];
            if ($cityname == "Тула") {
                $time = date('G');
                if ($time < 12)
                    $resultprice = (int)$arConfig["tula1"]["VALUE"];
                else
                    $resultprice = (int)$arConfig["tula2"]["VALUE"];
            } elseif ($cityname == "Москва") {
                $resultprice = (int)$arOrder["PRICE"] * (int)$arConfig["moscow"]["VALUE"] / 100;
            } else {
                $resultprice = (int)$arConfig["others"]["VALUE"];
            }
            return array(
                "RESULT" => "OK",
                "VALUE" => "$resultprice"
            );
        } else
            return array(
                "RESULT" => "ERROR",
            );
    }
}
AddEventHandler("sale", "onSaleDeliveryHandlersBuildList", array('MTulaExpress', 'Init')); 