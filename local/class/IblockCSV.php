<?php
/**
 * Created by PhpStorm.
 * User: STANKEVICH
 * Date: 07.07.2016
 * Time: 13:01
 */

namespace maximaster;


class IblockCSV
{
    private $csv_direct = null;
    private $idBlock = null;
    private $codeBlock = null;

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
        $handle = fopen($this->csv_direct, "r");

        $array_csv = array();
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) {
            $array_csv[] = $line;
        }
        fclose($handle);
        return $array_csv;
    }

    public function Import()
    {
        if (\CModule::IncludeModule("iblock")) {
            $data_array = $this->getCSV();
            foreach ($data_array as $value) {
                switch ($value[0]) {
                    case "iblock":
                        $this->ImportIB($value);
                        break;
                    case "section":
                        $this->ImportSect($value);
                        break;
                    case "element":
                        $this->ImportElem($value);
                        break;
                    default:
                        throw new \Exception("$value[0] не является индификатором");
                }
            }
            \CIBlockSection::ReSort($this->idBlock);
        }
    }

    private function ImportIB($line)
    {
        $ib = new \CIBlock;
        $arFields = Array(
            "NAME" => $line[2],
            "CODE" => $line[1],
            "IBLOCK_TYPE_ID" => "content",
            "DESCRIPTION_TYPE" => "text"
        );
        $Emp = \CIBlock::GetList(
            Array(),
            Array(
                "CODE" => $line[1],
            )
        );
        $this->codeBlock = $line[1];
        if ($ob = $Emp->GetNext()) {
            $this->idBlock = $ob["ID"];
            $ib->Update($this->idBlock, $arFields);
        } else {
            throw new \Exception("Инфоблок $line[1] не найден");
            /*$this->idBlock = $ib->Add($arFields);*/
        }
    }

    private function Parent($codeParent, $codeChild)
    {
        $idParent = "";
        if ($codeParent !== $this->codeBlock) {
            $parent = \CIBlockSection::GetList(
                Array(),
                Array(
                    "CODE" => $codeParent
                )
            );
            if ($ob = $parent->GetNext()) {
                $idParent = $ob["ID"];
            } else {
                throw new \Exception("Родитель ($codeParent) элемента ($codeChild) не найден");
            }
        }
        return $idParent;
    }

    private function ImportSect($line)
    {
        $bs = new \CIBlockSection;
        $idParent = $this->Parent($line[1], $line[2]);
        /*
         * В случае успеха удалить
        $arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/images/$line[5]");
        $arIMAGE["old_file"] =
        $arIMAGE["del"] =
        $arIMAGE["MODULE_ID"] =
        */
        $arFields = Array(
            "IBLOCK_SECTION_ID" => $idParent,
            "IBLOCK_ID" => $this->idBlock,
            "CODE" => $line[2],
            "NAME" => $line[3],
            "SORT" => $line[4],
            "DETAIL_PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            /*CFile::SaveFile($arIMAGE, $_SERVER["DOCUMENT_ROOT"] . "/images/$line[5]"),*/
            "DESCRIPTION" => $line[6],
            "DESCRIPTION_TYPE" => "text"
        );
        $Emp = \CIBlockSection::GetList(
            Array(),
            Array(
                "CODE" => $line[2],
            )
        );
        if ($ob = $Emp->GetNext()) {
            $bs->Update($ob["ID"], $arFields, false);
        } else {
            $bs->Add($arFields, false);
        }
    }

    private function ImportElem($line)
    {
        $el = new \CIBlockElement;
        $idParent = $this->Parent($line[1], $line[2]);
        $PROP = array(
            "PRICE" => $line[8],
            "NUMBER" => $line[9],
            "COUNTRY" => $line[10],
            /*"BRAND"=>$line[11],*/
        );
        $arFields = Array(
            "IBLOCK_SECTION_ID" => $idParent,
            "IBLOCK_ID" => $this->idBlock,
            "PROPERTY_VALUES" => $PROP,
            "CODE" => $line[2],
            "NAME" => $line[3],
            "SORT" => $line[4],
            "DETAIL_PICTURE" => \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/local/images/$line[5]"),
            "PREVIEW_TEXT" => $line[6],
            "DETAIL_TEXT" => $line[7]
        );
        $Emp = \CIBlockElement::GetList(
            Array(),
            Array(
                "CODE" => $line[2],
            )
        );
        if ($ob = $Emp->GetNext()) {
            $el->Update($ob["ID"], $arFields, false);
        } else {
            $el->Add($arFields, false);
        }
    }
}