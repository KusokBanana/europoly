<?php
function getXLS($xls){
    require_once dirname(__FILE__) . "/../Classes/PHPExcel/IOFactory.php";
    $objPHPExcel = PHPExcel_IOFactory::load($xls);
    $objPHPExcel->setActiveSheetIndex(0);

//    $aSheet = $objPHPExcel->getActiveSheet();
    $sheets = $objPHPExcel->getAllSheets();

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '9000');

    //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
    $array = array();
    $i = 0;
    foreach ($sheets as $aSheet) {

//        if ($i == 5)
//            return $array;

//        $i++;
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
                if ($rowIndex < 2) {
                    setHeader($header, $value, $colNumber);
                } else {
                    if (isset($header[$colNumber]) && $headerName = $header[$colNumber]) {
                        if ($headerName == 'construction_id' && !is_numeric($value))
                            continue;
                        $item[$headerName] = $value;
                    }
                }
            }

            $baseArrayItem = [
                'article' => ['val' => isset($item['article']) ? $item['article'] : null, 'type' => 'string'],
                'name' => ['val' => isset($item['name']) ? $item['name'] : null, 'type' => 'string'],
                'category_id' => ['val' => isset($item['category_id']) ? $item['category_id'] : null, 'type' => 'int'],
                'brand' => ['val' => isset($item['brand']) ? $item['brand'] : null],
                'country' => ['val' => isset($item['country']) ? $item['country'] : null, 'type' => 'string'],
                'collection' => ['val' => isset($item['collection']) ? $item['collection'] : null, 'type' => 'string'],
                'wood_id' => ['val' => isset($item['wood_id']) ? $item['wood_id'] : null, 'type' => 'int'],
                'additional_info' => ['val' => isset($item['additional_info']) ? $item['additional_info'] : null, 'type' => 'string'],
                'color_id' => ['val' => isset($item['color_id']) ? $item['color_id'] : null, 'type' => 'int'],
                'color2_id' => ['val' => isset($item['color2_id']) ? $item['color2_id'] : null, 'type' => 'int'],
                'color' => ['val' => isset($item['color']) ? $item['color'] : null, 'type' => 'string'],
                'grading' => ['val' => isset($item['grading']) ? $item['grading'] : null, 'type' => 'string'],
                'grading_rus' => ['val' => isset($item['grading_rus']) ? $item['grading_rus'] : null, 'type' => 'string'],
                'thickness' => ['val' => isset($item['thickness']) ? $item['thickness'] : null, 'type' => 'int'],
                'width' => ['val' => isset($item['width']) ? $item['width'] : null, 'type' => 'string'],
                'length' => ['val' => isset($item['length']) ? $item['length'] : null, 'type' => 'string'],
                'texture' => ['val' => isset($item['texture']) ? $item['texture'] : null, 'type' => 'string'],
                'layer' => ['val' => isset($item['layer']) ? $item['layer'] : null, 'type' => 'string'],
                'installation' => ['val' => isset($item['installation']) ? $item['installation'] : null, 'type' => 'string'],
                'surface' => ['val' => isset($item['surface']) ? $item['surface'] : null, 'type' => 'string'],
                'construction_id' => ['val' => isset($item['construction_id']) ? $item['construction_id'] : null, 'type' => 'int'],
                'construction' => ['val' => isset($item['construction']) ? $item['construction'] : null, 'type' => 'string'],
                'units' => ['val' => isset($item['units']) ? $item['units'] : null, 'type' => 'string'],
                'packing_type' => ['val' => isset($item['packing_type']) ? $item['packing_type'] : null, 'type' => 'string'],
                'weight' => ['val' => isset($item['weight']) ? $item['weight'] : null, 'type' => 'float'],
                'amount_in_pack' => ['val' => isset($item['amount_in_pack']) ? $item['amount_in_pack'] : null, 'type' => 'float'],
                'purchase_price' => ['val' => isset($item['purchase_price']) ? $item['purchase_price'] : null, 'type' => 'double'],
                'purchase_price_currency' => ['val' => isset($item['purchase_price_currency']) ? $item['purchase_price_currency'] :
                    null, 'type' => 'string'],
                'suppliers_discount' => ['val' => isset($item['suppliers_discount']) ? $item['suppliers_discount'] : null, 'type' => 'float'],
                'margin' => ['val' => isset($item['margin']) ? $item['margin'] : null, 'type' => 'float'],
                'pattern_id' => ['val' => isset($item['pattern_id']) ? $item['pattern_id'] : null, 'type' => 'int'],
                'sheet' => ['val' => $aSheet->getTitle(), 'type' => 'string'],
//
                'country_rus' => ['val' => isset($item['country_rus']) ? $item['country_rus'] : null, 'type' => 'string'],
                'collection_rus' => ['val' => isset($item['collection_rus']) ? $item['collection_rus'] : null, 'type' => 'string'],
                'additional_info_rus' => ['val' => isset($item['additional_info_rus']) ? $item['additional_info_rus'] : null, 'type' => 'string'],
                'texture_rus' => ['val' => isset($item['texture_rus']) ? $item['texture_rus'] : null, 'type' => 'string'],
                'layer_rus' => ['val' => isset($item['layer_rus']) ? $item['layer_rus'] : null, 'type' => 'string'],
                'installation_rus' => ['val' => isset($item['installation_rus']) ? $item['installation_rus'] : null, 'type' => 'string'],
                'surface_rus' => ['val' => isset($item['surface_rus']) ? $item['surface_rus'] : null, 'type' => 'string'],
                'units_rus' => ['val' => isset($item['units_rus']) ? $item['units_rus'] : null, 'type' => 'string'],
                'packing_type_rus' => ['val' => isset($item['packing_type_rus']) ? $item['packing_type_rus'] : null, 'type' => 'string'],
                'sell_price' => ['val' => isset($item['sell_price']) ? $item['sell_price'] : null, 'type' => 'float'],

                'image_id_A' => ['val' => isset($item['image_id_A']) ? $item['image_id_A'] : null, 'type' => 'string'],
                'image_id_B' => ['val' => isset($item['image_id_B']) ? $item['image_id_B'] : null, 'type' => 'string'],
                'image_id_V' => ['val' => isset($item['image_id_V']) ? $item['image_id_V'] : null, 'type' => 'string'],

                'grading_id' => ['val' => isset($item['grading_id']) ? $item['grading_id'] : null, 'type' => 'int'],
                'texture_id' => ['val' => isset($item['texture_id']) ? $item['texture_id'] : null, 'type' => 'int'],
                'texture2_id' => ['val' => isset($item['texture2_id']) ? $item['texture2_id'] : null, 'type' => 'int'],
                'pattern' => ['val' => isset($item['pattern']) ? $item['pattern'] : null, 'type' => 'string'],
                'pattern_rus' => ['val' => isset($item['pattern_rus']) ? $item['pattern_rus'] : null, 'type' => 'string'],
                'color_rus' => ['val' => isset($item['color_rus']) ? $item['color_rus'] : null, 'type' => 'string'],
                'construction_rus' => ['val' => isset($item['construction_rus']) ? $item['construction_rus'] : null, 'type' => 'string'],
                'amount_of_units_in_pack' => ['val' => isset($item['amount_of_units_in_pack']) ? $item['amount_of_units_in_pack'] :
                    null, 'type' => 'float'],
                'amount_of_packs_in_pack' => ['val' => isset($item['amount_of_packs_in_pack']) ? $item['amount_of_packs_in_pack'] :
                    null, 'type' => 'float'],
                'sell_price_currency' => ['val' => isset($item['sell_price_currency']) ? $item['sell_price_currency'] :
                    null, 'type' => 'string'],
                'supplier' => ['val' => isset($item['supplier']) ? $item['supplier'] : null, 'type' => 'string'],
                'visual_name' => ['val' => isset($item['visual_name']) ? $item['visual_name'] : null, 'type' => 'string'],
                'visual_name_rus' => ['val' => isset($item['visual_name_rus']) ? $item['visual_name_rus'] : null, 'type' => 'string'],
            ];

            //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
            if ($rowIndex >= 2)
                array_push($array, $baseArrayItem);
        }
    }
    return $array;
}
global $parser;
$pars = [];
for ($fileI = 1; $fileI <= 17; $fileI++) {
	$fileName = "$fileI.xlsx";
	$pars = array_merge($pars,
		getXLS(dirname(__FILE__) . "/../../../dump_db/raw_data/catalogue/$fileName")); //извлеаем данные из XLS
}
$parser = $pars;
//$fileName = 'products_24_04_v2.xlsx';
//$parser = getXLS(dirname(__FILE__) . "/../$fileName"); //извлеаем данные из XLS

function setHeader(&$header, $value, $cellNumber) {
    switch($value) {
        case 'Артикул/Article':
            $header[$cellNumber] = 'article';
            break;
        case 'Name/ Wood':
            $header[$cellNumber] = 'name';
            break;
        case 'Название/ Порода дерева':
            $header[$cellNumber] = 'name_rus';
            break;
        case 'Категория КОД':
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
        case 'Порода дерева КОД':
            $header[$cellNumber] = 'wood_id';
            break;
        case 'Additional characteristics':
            $header[$cellNumber] = 'additional_info';
            break;
        case 'Цвет (из 9 вариантов) 1':
            $header[$cellNumber] = 'color_id';
            break;
        case 'Цвет (из 9 вариантов) 2':
            $header[$cellNumber] = 'color2_id';
            break;
        case 'Color':
            $header[$cellNumber] = 'color';
            break;
        case 'Селекция':
            $header[$cellNumber] = 'grading_rus';
            break;
        case 'Grading':
            $header[$cellNumber] = 'grading';
            break;
        case 'Толщина/Thickness':
            $header[$cellNumber] = 'thickness';
            break;
        case 'Ширина/Width':
            $header[$cellNumber] = 'width';
            break;
        case 'Длина/Length':
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
        case 'Тип конструкции КОД':
            $header[$cellNumber] = 'construction_id';
            break;
        case 'Тип конструкции':
            $header[$cellNumber] = 'construction_rus';
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
        case 'Вес 1 единицы измерения':
        case 'Вес 1 единицы измерения/Weight of 1 unit':
            $header[$cellNumber] = 'weight';
            break;
        case 'Количество товара в 1 упаковке (в единицах измерения)/Amount of product in 1 pack (in units)':
            $header[$cellNumber] = 'amount_in_pack';
            break;
        case 'Закупочная цена/Purchase Price':
            $header[$cellNumber] = 'purchase_price';
            break;
        case 'Валюта/Purchase Currency':
            $header[$cellNumber] = 'purchase_price_currency';
            break;
        case "Скидка от производителя/Supplier's Discount":
            $header[$cellNumber] = 'suppliers_discount';
            break;
        case 'Коэффициент для расчета себестоимости/for calculating the cost price':
            $header[$cellNumber] = 'margin';
            break;
        case 'Паттерн КОД':
            $header[$cellNumber] = 'pattern_id';
            break;
        case 'Страна':
            $header[$cellNumber] = 'country_rus';
            break;
        case 'Коллекция':
            $header[$cellNumber] = 'collection_rus';
            break;
        case 'Дополнительные характеристики':
            $header[$cellNumber] = 'additional_info_rus';
            break;
        case 'Текстура поверхности':
            $header[$cellNumber] = 'texture_rus';
            break;
        case 'Нижний слой/ Средний слой (для панелей Admonter)':
            $header[$cellNumber] = 'layer_rus';
            break;
        case 'Способ укладки':
            $header[$cellNumber] = 'installation_rus';
            break;
        case 'Покрытие поверхности':
            $header[$cellNumber] = 'surface_rus';
            break;
        case 'Единица измерения':
            $header[$cellNumber] = 'units_rus';
            break;
        case 'Тип упаковки':
            $header[$cellNumber] = 'packing_type_rus';
            break;
        case 'Розничная цена/ Retail price':
            $header[$cellNumber] = 'sell_price';
            break;

        case 'Фото вблизи (А)':
            $header[$cellNumber] = 'image_id_A';
            break;
        case 'Фото издалека (Б)':
            $header[$cellNumber] = 'image_id_B';
            break;
        case 'Фото под углом (В)':
            $header[$cellNumber] = 'image_id_V';
            break;

        case 'Селекция КОД':
            $header[$cellNumber] = 'grading_id';
            break;
        case 'Текстура поверхности 1 КОД':
            $header[$cellNumber] = 'texture_id';
            break;
        case 'Текстура поверхности 2 КОД':
            $header[$cellNumber] = 'texture2_id';
            break;
        case 'Раздел коллекции/Паттерн':
            $header[$cellNumber] = 'pattern_rus';
            break;
        case 'Collection section/Pattern':
            $header[$cellNumber] = 'pattern';
            break;
        case 'Цвет':
            $header[$cellNumber] = 'color_rus';
            break;
        case 'кол-во единиц измерения в 1 шт/q-ty of units in 1 pc.':
            $header[$cellNumber] = 'amount_of_units_in_pack';
            break;
        case 'Количество шт в 1 упаковке/number of pcs in 1 pack':
            $header[$cellNumber] = 'amount_of_packs_in_pack';
            break;
        case 'Валюта/Retail Currency':
            $header[$cellNumber] = 'sell_price_currency';
            break;
        case 'Поставщик/Supplier':
            $header[$cellNumber] = 'supplier';
            break;
        case 'НАИМЕНОВАНИЕ ДЛЯ СЧЕТА':
            $header[$cellNumber] = 'visual_name_rus';
            break;
        case 'НАИМЕНОВАНИЕ НА АНГЛИЙСКОМ':
            $header[$cellNumber] = 'visual_name';
            break;
    }
}
