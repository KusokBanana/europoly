<?php
function getXLS($xls){
    require_once dirname(__FILE__) . "/../Classes/PHPExcel/IOFactory.php";
    $objPHPExcel = PHPExcel_IOFactory::load($xls);
    $objPHPExcel->setActiveSheetIndex(0);

    $sheet = $objPHPExcel->getActiveSheet();

    ini_set('memory_limit', '-1');

    //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
    $array = array();
    //получим итератор строки и пройдемся по нему циклом
    foreach ($sheet->getRowIterator() as $row) {
        //получим итератор ячеек текущей строки
        $cellIterator = $row->getCellIterator();
        $rowIndex = $row->getRowIndex();

        if ($rowIndex < 2)
            continue;

        //пройдемся циклом по ячейкам строки
        //этот массив будет содержать значения каждой отдельной строки
        $item = array();
        $cellIterator->setIterateOnlyExistingCells(false);

        foreach ($cellIterator as $key => $cell) {
            //заносим значения ячеек одной строки в отдельный массив
            $value = $cell->getFormattedValue();
//            $colNumber = $cell->getColumn();
            if (!$value) {
                continue;
            }

            $item[$key] = $value;
        }

        //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
        array_push($array, $item);
    }
    return $array;
}

global $parser;
$parser = getXLS(dirname(__FILE__) . '/../expenses.xlsx'); //извлеаем данные из XLS
