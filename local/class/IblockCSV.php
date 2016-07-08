<?php
namespace maximaster;


class IblockCSV
{
    private $csv_direct = null;
    private $idBlock = null;
    private $codeBlock = null;
    private $activSectId = array();
    private $activElemId = array();

    public function __construct($csv_file)
    {
        if (file_exists($csv_file)) {
            $this->csv_direct = $csv_file;
        } else {
            throw new \Exception("Файл $csv_file не найден");
        }
    }

    /*Получаем массив данных из CSV*/
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

    /*Импортируем данные в инфоблок*/
    public function Import()
    {
        if (\CModule::IncludeModule("iblock")) {
            $data_array = $this->getCSV();//Получаем массив данных
            foreach ($data_array as $value) {//цикл по массиву
                switch ($value[0]) {//смотрим индификатор строки
                    case "iblock":
                        $this->ImportIB($value);//получаем id и символьный код инфоблока
                        break;
                    case "section":
                        $this->ImportSect($value);//импортируем раздщел
                        break;
                    case "element":
                        $this->ImportElem($value);//импортируем элемент
                        break;
                    default:
                        throw new \Exception("$value[0] не является индификатором");
                }
            }
            $this->Deactivation();//деактивируем разделы и элементы инфоблока не присутствующие в csv
            \CIBlockSection::ReSort($this->idBlock);//сортируем разделы
        }
    }

    /*Получение id и символьного кода инфоблока*/
    private function ImportIB($line)
    {
        $ib = new \CIBlock;
        //Заполняем поля для обновления
        $arFields = Array(
            "NAME" => $line[2],
            "CODE" => $line[1],
            "IBLOCK_TYPE_ID" => "content",
            "DESCRIPTION_TYPE" => "text"
        );
        //Ищем инфоблок по символьному коду
        $Emp = \CIBlock::GetList(
            Array(),
            Array(
                "CODE" => $line[1],
            )
        );
        //Проверяем наличие инфоблока
        if ($ob = $Emp->GetNext()) {
            $this->codeBlock = $line[1];//получаем код
            $this->idBlock = $ob["ID"];//получаем ид
            $ib->Update($this->idBlock, $arFields);//обновляем инфоблок
        } else {
            throw new \Exception("Инфоблок $line[1] не найден");
        }
    }

    /*Возвращает id родителя*/
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

    /*Импортируем раздел*/
    private function ImportSect($line)
    {
        //иницифлизируем раздел
        $bs = new \CIBlockSection;
        //ищем родителя
        $idParent = $this->Parent($line[1], $line[2]);
        //заполняем поля для обновления/добавления
        $arFields = Array(
            "IBLOCK_SECTION_ID" => $idParent,
            "IBLOCK_ID" => $this->idBlock,
            "ACTIVE" => "Y",
            "CODE" => $line[2],
            "NAME" => $line[3],
            "SORT" => $line[4],
            "PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            "DESCRIPTION" => $line[6],
            "DESCRIPTION_TYPE" => "text"
        );
        //ищем раздел
        $Emp = \CIBlockSection::GetList(
            Array(),
            Array(
                "CODE" => $line[2],
            )
        );
        $ID = null;
        //проверяем наличие раздела
        if ($ob = $Emp->GetNext()) {
            $ID = $ob["ID"];
            $bs->Update($ID, $arFields, false);//обновляем сушествующий раздел

        } else {
            $ID = $bs->Add($arFields, false);//создаем раздел
        }
        $this->activSectId[] = $ID;//заполняем массив активных секций
    }

    /*Импортируем элемент*/
    private function ImportElem($line)
    {
        //инициализируем элемент
        $el = new \CIBlockElement;
        //ищем родителя
        $idParent = $this->Parent($line[1], $line[2]);
        //заполняем поля для обновления/добавления
        $PROP = array(
            "PRICE" => $line[8],
            "NUMBER" => $line[9],
            "COUNTRY" => $line[10],
            "BRAND" => $line[11]
        );
        $arFields = Array(
            "IBLOCK_SECTION_ID" => $idParent,
            "IBLOCK_ID" => $this->idBlock,
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => $PROP,
            "CODE" => $line[2],
            "NAME" => $line[3],
            "SORT" => $line[4],
            "DETAIL_PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            "PREVIEW_PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            "PREVIEW_TEXT" => $line[6],
            "DETAIL_TEXT" => $line[7]
        );
        //ищем элемент
        $Emp = \CIBlockElement::GetList(
            Array(),
            Array(
                "CODE" => $line[2],
            )
        );
        $ID = null;
        //проверяем наличие элемента
        if ($ob = $Emp->GetNext()) {
            $ID = $ob["ID"];
            $el->Update($ID, $arFields, false);//обновляем сушествующий элемнт
        } else {
            $ID = $el->Add($arFields, false);//создаем элемент
        }
        $this->activElemId[] = $ID;//заполняем массив активных элементов
    }

    /*Деактивируем разделы и элементы*/
    private function Deactivation()
    {
        $D = new \CIBlockElement;//инициализируем элемент
        //Фильтр поиска по активным элементам инфоблока не включенных в список активности
        $arFilter = Array(
            "IBLOCK_ID" => $this->idBlock,
            "!ID" => $this->activElemId,
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
        $arFilter = Array(
            "IBLOCK_ID" => $this->idBlock,
            "!ID" => $this->activSectId,
            "ACTIVE" => "Y"
        );
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