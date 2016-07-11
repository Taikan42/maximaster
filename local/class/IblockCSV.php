<?php
namespace maximaster;


class IblockCSV
{
    private $csv_direct = null;
    private $idBlock = null;
    private $codeBlock = null;
    const IDENTIFIER = 0;
    const PARENT_CODE = 1;
    const CODE = 2;
    const NAME = 3;
    const SORT = 4;
    const IMAGE = 5;
    const PREVIEW_TEXT = 6;
    const DETAIL_TEXT = 7;
    const PRICE = 8;
    const NUMBER = 9;
    const COUNTRY = 10;
    const BREND = 11;

    public function __construct($csv_file)
    {
        if (file_exists($csv_file)) {
            $this->csv_direct = $csv_file;
        } else {
            throw new \Exception("Файл $csv_file не найден");
        }
    }

    /**
     * Получаем массив данных из CSV
     * @return array
     */
    private function getCSV()
    {
        $handle = fopen($this->csv_direct, "r");//открываем файл для чтения
        $array_csv = array();
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) {//заполняем массив данными
            $array_csv[] = $line;
        }
        fclose($handle);//закрываем файл
        return $array_csv;
    }

    /**
     * Импортируем данные в инфоблок
     * @throws \Exception
     */
    public function Import()
    {
        if (\CModule::IncludeModule("iblock")) {
            $data_array = $this->getCSV();//Получаем массив данных
            foreach ($data_array as $value) {//цикл по массиву
                switch ($value[self::IDENTIFIER]) {//смотрим индификатор строки
                    case "iblock":
                        $this->ImportIB($value);//получаем id и символьный код инфоблока
                        break;
                    case "section":
                        $this->ImportSect($value);//импортируем раздел
                        break;
                    case "element":
                        $this->ImportElem($value);//импортируем элемент
                        break;
                    default:
                        throw new \Exception($value[self::IDENTIFIER] . "не является идентификатором");
                }
            }
            \CIBlockSection::ReSort($this->idBlock);//сортируем разделы
        }
    }

    /**
     * Получение id и символьного кода инфоблока
     * @param $line
     * @throws \Exception
     */
    private function ImportIB($line)
    {
        $ib = new \CIBlock;
        //Заполняем поля для обновления
        $arFields = Array(
            "NAME" => $line[self::CODE],
            "CODE" => $line[self::PARENT_CODE],
            "IBLOCK_TYPE_ID" => "content",
            "DESCRIPTION_TYPE" => "text"
        );
        //Ищем инфоблок по символьному коду
        $Emp = \CIBlock::GetList(
            Array(),
            Array(
                "CODE" => $line[self::PARENT_CODE],
            )
        );
        //Проверяем наличие инфоблока
        if ($ob = $Emp->GetNext()) {
            $this->codeBlock = $line[self::PARENT_CODE];//получаем код
            $this->idBlock = $ob["ID"];//получаем ид
            $ib->Update($this->idBlock, $arFields);//обновляем инфоблок
            $this->Deactivation();//деактивируем разделы и элементы инфоблока
        } else {
            throw new \Exception("Инфоблок" . $line[self::PARENT_CODE] . "не найден");
        }
    }

    /**
     * Возвращает id родителя
     * @param $codeParent
     * @param $codeChild
     * @return string
     * @throws \Exception
     */
    private function Parent($codeParent, $codeChild)
    {
        $idParent = "";//Если каталог корневой то id будет пустым
        //Проверяем раздел на нахождение в корне (родитель-инфоблок)
        if ($codeParent !== $this->codeBlock) {
            //Ищем раздел родителя по символьному коду
            $parent = \CIBlockSection::GetList(
                Array(),
                Array(
                    "CODE" => $codeParent
                )
            );
            //проверяем наличие раздела
            if ($ob = $parent->GetNext()) {
                $idParent = $ob["ID"];
            } else {
                throw new \Exception("Родитель ($codeParent) элемента ($codeChild) не найден");
            }
        }
        return $idParent;
    }

    /**
     * Импортируем раздел
     * @param $line
     * @throws \Exception
     */
    private function ImportSect($line)
    {
        //иницифлизируем раздел
        $bs = new \CIBlockSection;
        //ищем родителя
        $idParent = $this->Parent($line[self::PARENT_CODE], $line[self::CODE]);
        //заполняем поля для обновления/добавления
        $arFields = Array(
            "IBLOCK_SECTION_ID" => $idParent,
            "IBLOCK_ID" => $this->idBlock,
            "ACTIVE" => "Y",
            "CODE" => $line[self::CODE],
            "NAME" => $line[self::NAME],
            "SORT" => $line[self::SORT],
            "PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/" . $line[self::IMAGE]),
            "DESCRIPTION" => $line[self::PREVIEW_TEXT],
            "DESCRIPTION_TYPE" => "text"
        );
        //ищем раздел
        $Emp = \CIBlockSection::GetList(
            Array(),
            Array(
                "CODE" => $line[self::CODE],
            )
        );
        $ID = null;
        //проверяем наличие раздела
        if ($ob = $Emp->GetNext()) {
            $bs->Update($ob["ID"], $arFields, false);//обновляем сушествующий раздел

        } else {
            $bs->Add($arFields, false);//создаем раздел
        }
    }

    /**
     * Импортируем элемент
     * @param $line string
     * @throws \Exception
     */
    private function ImportElem($line)
    {
        //инициализируем элемент
        $el = new \CIBlockElement;
        //ищем родителя
        $idParent = $this->Parent($line[self::PARENT_CODE], $line[self::CODE]);
        //заполняем поля для обновления/добавления
        $PROP = array(
            "PRICE" => $line[self::PRICE],
            "NUMBER" => $line[self::NUMBER],
            "COUNTRY" => $line[self::COUNTRY],
            "BRAND" => $line[self::BREND]
        );
        $arFields = Array(
            "IBLOCK_SECTION_ID" => $idParent,
            "IBLOCK_ID" => $this->idBlock,
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => $PROP,
            "CODE" => $line[self::CODE],
            "NAME" => $line[self::NAME],
            "SORT" => $line[self::SORT],
            "DETAIL_PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            "PREVIEW_PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            "PREVIEW_TEXT" => $line[self::PREVIEW_TEXT],
            "DETAIL_TEXT" => $line[self::DETAIL_TEXT]
        );
        //ищем элемент
        $Emp = \CIBlockElement::GetList(
            Array(),
            Array(
                "CODE" => $line[self::CODE],
            )
        );
        $ID = null;
        $arPriceFields = Array(
            "CATALOG_GROUP_ID" => 1,
            "CURRENCY" => "RUB",
            "PRICE" => $line[self::PRICE]
        );
        //проверяем наличие элемента
        if ($ob = $Emp->GetNext()) {
            $ID = $ob["ID"];

            /*
            \CCatalogProduct::Update($ID, ["QUANTITY" => $line[self::NUMBER]]);
            $res = \CPrice::GetList([],["PRODUCT_ID" => $ID,"CATALOG_GROUP_ID" => 1]);
            $arr = $res->Fetch();
            \CPrice::Update($arr["ID"], $arPriceFields);
            */
        } else {
            $ID = $el->Add($arFields, false);//создаем элемент

            /*
            \CCatalogProduct::Add(["ID" => $ID, "QUANTITY" => $line[self::NUMBER]]);
            $arPriceFields["PRODUCT_ID"] = $ID;
            \CPrice::Add($arPriceFields);
            */
        }
        $Emp = \CCatalogProduct::GetList(
            array(),
            array("ID" => $ID),
            false,
            array()
        );
        if ($ob = $Emp->GetNext()){
            \CCatalogProduct::Update($ID, ["QUANTITY" => $line[self::NUMBER]]);
            $res = \CPrice::GetList([],["PRODUCT_ID" => $ID,"CATALOG_GROUP_ID" => 1]);
            $arr = $res->Fetch();
            \CPrice::Update($arr["ID"], $arPriceFields);
        } else {
            \CCatalogProduct::Add(["ID" => $ID, "QUANTITY" => $line[self::NUMBER]]);
            $arPriceFields["PRODUCT_ID"] = $ID;
            \CPrice::Add($arPriceFields);
        }
    }

    /**
     * Деактивируем разделы и элементы
     */
    private function Deactivation()
    {
        $D = new \CIBlockElement;//инициализируем элемент
        $arFilter = Array(
            "IBLOCK_ID" => $this->idBlock,
            "ACTIVE" => "Y"
        );
        //ищем элементы
        $res = \CIBlockElement::GetList(
            Array(),
            $arFilter
        );
        while ($ob = $res->GetNext()) {
            $D->Update($ob["ID"], Array("ACTIVE" => "N"), false);//деактивируем
        }
        $D = new \CIBlockSection;//инициализируем раздел
        //Фильтр поиска по активным разделам инфоблока не включенных в список активности
        //ищем разделы
        $res = \CIBlockSection::GetList(
            Array(),
            $arFilter
        );
        while ($ob = $res->GetNext()) {
            $D->Update($ob["ID"], Array("ACTIVE" => "N"), false);//деактивируем
        }
    }
}