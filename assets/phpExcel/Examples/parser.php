<?php
function getXLS($xls){
    require_once dirname(__FILE__) . "/../Classes/PHPExcel/IOFactory.php";
    $objPHPExcel = PHPExcel_IOFactory::load($xls);
    $objPHPExcel->setActiveSheetIndex(0);
//    $aSheet = $objPHPExcel->getActiveSheet();

    $sheets = $objPHPExcel->getAllSheets();

    ini_set('memory_limit', '-1');

    //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
    $array = array();
    foreach ($sheets as $aSheet) {
        //получим итератор строки и пройдемся по нему циклом
        $header = [];
        foreach ($aSheet->getRowIterator() as $row) {
            //получим итератор ячеек текущей строки
            $cellIterator = $row->getCellIterator();
            $rowIndex = $row->getRowIndex();

            //пройдемся циклом по ячейкам строки
            //этот массив будет содержать значения каждой отдельной строки
            $item = array();
            $cellIterator->setIterateOnlyExistingCells(false);



            foreach ($cellIterator as $cell) {
                //заносим значения ячеек одной строки в отдельный массив
                $value = $cell->getFormattedValue();
                $colNumber = $cell->getColumn();
                if ($rowIndex < 3) {
                    setHeader($header, $value, $colNumber);
                } else {
                    if (isset($header[$colNumber]) && $headerName = $header[$colNumber]) {
                        $item[$headerName] = $value;
                    }
                }
            }


            $baseArrayItem = [
                'article' => ['val' => isset($item['article']) ? $item['article'] : '', 'type' => 'string'],
                'name' => ['val' => isset($item['name']) ? $item['name'] : '', 'type' => 'string'],
                'category_id' => ['val' => isset($item['category_id']) ? $item['category_id'] : '', 'type' => 'int'],
                'brand' => ['val' => isset($item['brand']) ? $item['brand'] : ''], // here replace by brand_id
                'country' => ['val' => isset($item['country']) ? $item['country'] : '', 'type' => 'string'],
                'collection' => ['val' => isset($item['collection']) ? $item['collection'] : '', 'type' => 'string'],
                'wood_id' => ['val' => isset($item['wood_id']) ? $item['wood_id'] : '', 'type' => 'int'],
                'additional_info' => ['val' => isset($item['additional_info']) ? $item['additional_info'] : '', 'type' => 'string'],
                'color_id' => ['val' => isset($item['color_id']) ? $item['color_id'] : '', 'type' => 'int'],
                'color2_id' => ['val' => isset($item['color2_id']) ? $item['color2_id'] : '', 'type' => 'int'],
                'color' => ['val' => isset($item['color']) ? $item['color'] : '', 'type' => 'string'],
                'grading' => ['val' => isset($item['grading']) ? $item['grading'] : ''], // here reaplce by grading_id
                'thickness' => ['val' => isset($item['thickness']) ? $item['thickness'] : '', 'type' => 'int'],
                'width' => ['val' => isset($item['width']) ? $item['width'] : '', 'type' => 'string'],
                'length' => ['val' => isset($item['length']) ? $item['length'] : '', 'type' => 'string'],
                'texture' => ['val' => isset($item['texture']) ? $item['texture'] : '', 'type' => 'string'],
                'layer' => ['val' => isset($item['layer']) ? $item['layer'] : '', 'type' => 'string'],
                'installation' => ['val' => isset($item['installation']) ? $item['installation'] : '', 'type' => 'string'],
                'surface' => ['val' => isset($item['surface']) ? $item['surface'] : '', 'type' => 'string'],
                'construction_id' => ['val' => isset($item['construction_id']) ? $item['construction_id'] : '', 'type' => 'int'],
                'construction' => ['val' => isset($item['construction']) ? $item['construction'] : '', 'type' => 'string'],
                'units' => ['val' => isset($item['units']) ? $item['units'] : '', 'type' => 'string'],
                'packing_type' => ['val' => isset($item['packing_type']) ? $item['packing_type'] : '', 'type' => 'string'],
                'weight' => ['val' => isset($item['weight']) ? $item['weight'] : '', 'type' => 'float'],
                'amount_in_pack' => ['val' => isset($item['amount_in_pack']) ? $item['amount_in_pack'] : '', 'type' => 'float'],
                'purchase_price' => ['val' => isset($item['purchase_price']) ? $item['purchase_price'] : '', 'type' => 'double'],
                'dealer_price' => ['val' => isset($item['dealer_price']) ? $item['dealer_price'] : '', 'type' => 'double'],
                'currency' => ['val' => isset($item['currency']) ? $item['currency'] : '', 'type' => 'string'],
                'suppliers_discount' => ['val' => isset($item['suppliers_discount']) ? $item['suppliers_discount'] : '', 'type' => 'int'],
                'margin' => ['val' => isset($item['margin']) ? $item['margin'] : '', 'type' => 'int'],
                'pattern_id' => ['val' => isset($item['pattern_id']) ? $item['pattern_id'] : '', 'type' => 'int'],
                'sheet' => ['val' => $aSheet->getTitle(), 'type' => 'string'],
            ];

            //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
            if ($rowIndex >= 3)
                array_push($array, $baseArrayItem);
        }
    }
    return $array;
}
global $parser;
$parser = getXLS(dirname(__FILE__) . '/../base.xlsx'); //извлеаем данные из XLS


function setHeader(&$header, $value, $cellNumber) {
    switch($value) {
        case 'Article':
            $header[$cellNumber] = 'article';
            break;
        case 'Name/ Wood':
            $header[$cellNumber] = 'name';
            break;
        case 'Категория':
            $header[$cellNumber] = 'category_id';
            break;
        case 'Brand':
            $header[$cellNumber] = 'brand';
            break;
        case 'Country':
            $header[$cellNumber] = 'country';
            break;
        case 'Collection':
            $header[$cellNumber] = 'collection';
            break;
        case 'Порода дерева':
            $header[$cellNumber] = 'wood_id';
            break;
        case 'Additional characteristics':
            $header[$cellNumber] = 'additional_info';
            break;
        case 'Цвет (из 9 вариантов)':
            if (in_array('color_id',$header))
                $header[$cellNumber] = 'color2_id';
            else
                $header[$cellNumber] = 'color_id';
            break;
        case 'Color':
            $header[$cellNumber] = 'color';
            break;
        case 'Grading':
            $header[$cellNumber] = 'grading';
            break;
        case 'Thickness':
            $header[$cellNumber] = 'thickness';
            break;
        case 'Width':
            $header[$cellNumber] = 'width';
            break;
        case 'Length':
            $header[$cellNumber] = 'length';
            break;
        case 'Texture':
            $header[$cellNumber] = 'texture';
            break;
        case 'Bottom layer/ Middle layer (for Admonter panels)':
            $header[$cellNumber] = 'layer';
            break;
        case 'Installation':
            $header[$cellNumber] = 'installation';
            break;
        case 'Surface':
            $header[$cellNumber] = 'surface';
            break;
        case 'Тип конструкции':
            $header[$cellNumber] = 'construction_id';
            break;
        case 'Construction':
            $header[$cellNumber] = 'construction';
            break;
        case 'Units':
            $header[$cellNumber] = 'units';
            break;
        case 'Packing Type':
            $header[$cellNumber] = 'packing_type';
            break;
        case 'Weight of 1 unit':
            $header[$cellNumber] = 'weight';
            break;
        case 'Amount of product in 1 pack (in units)':
            $header[$cellNumber] = 'amount_in_pack';
            break;
        case 'Purchase Price':
            $header[$cellNumber] = 'purchase_price';
            break;
        case 'Currency':
            $header[$cellNumber] = 'currency';
            break;
        case "Supplier's Discount":
            $header[$cellNumber] = 'suppliers_discount';
            break;
        case 'Margin':
            $header[$cellNumber] = 'margin';
            break;
        case 'Паттерн':
            $header[$cellNumber] = 'pattern_id';
            break;
    }
}