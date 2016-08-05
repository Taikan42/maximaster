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
            "DESCRIPTION_INNER" => "Обработчик доставки \"Тула экспресс\" Для функционирования необходимо"
                . " заполнение всех полей стоимости доставки",
            "BASE_CURRENCY" => COption::GetOptionString("sale", "default_currency", "RUB"),
            "HANDLER" => __FILE__,
            "DBGETSETTINGS" => array("MTulaExpress", "GetSettings"),
            "DBSETSETTINGS" => array("MTulaExpress", "SetSettings"),
            "GETCONFIG" => array("MTulaExpress", "GetConfig"),
            "COMPABILITY" => array("MTulaExpress", "Compability"),
            "CALCULATOR" => array("MTulaExpress", "Calculate"),

            "PROFILES" => array(
                "base" => array(
                    "TITLE" => "Express",
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
                "price" => "Стоимость доставки",
            ),
            "CONFIG" => array(
                "tula1" => array(
                    "TITLE" => "Доставка по Туле в первой половине дня",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "price"),
                "tula2" => array(
                    "TITLE" => "Доставка по Туле во второй половине дня",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "price"),
                "moscow" => array(
                    "TITLE" => "Наценка при доставке в Москву в %",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "price"),
                "nodeliver" => array(
                    "TITLE" => "Не доставлять в города на букву",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "price"),
                "others" => array(
                    "TITLE" => "Цена для остальных случаев",
                    "TYPE" => "STRING",
                    "DEFAULT" => "",
                    "GROUP" => "price"),
            )
        );
        return $arConfig;
    }

    function SetSettings($arSettings)
    {
        return serialize($arSettings);
    }

    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    function Compability($arOrder, $arConfig)
    {
        $check = true;
        foreach ($arConfig as $arItem){
            if ($arItem["VALUE"] == "") $check = false;
        }
        $res_loc = CSaleLocation::GetList(
            array(),
            array("ID" => $arOrder["LOCATION_TO"]),
            false,
            false,
            array()
        );
        if ($ob = $res_loc->fetch()) {//Проверка наличия местоположения
            $char = mb_substr($ob["CITY_NAME"], 0, 1, "UTF-8");
            if ($char == $arConfig["nodeliver"]["VALUE"]) {
                $check = false;
            }
        } else {
            $check = false;
        }
        if ($check){
            return array('base');
        } else{
            return array();
        }
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
                "VALUE" => $resultprice
            );
        } else
            return array(
                "RESULT" => "ERROR"
            );
    }
}

AddEventHandler("sale", "onSaleDeliveryHandlersBuildList", array('MTulaExpress', 'Init'));