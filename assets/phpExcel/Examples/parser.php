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
                        if ($headerName == 'construction_id' && !is_numeric($value))
                            continue;
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
                'grading' => ['val' => isset($item['grading']) ? $item['grading'] : '', 'type' => 'string'],
                'grading_rus' => ['val' => isset($item['grading_rus']) ? $item['grading_rus'] : '', 'type' => 'string'],
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
                'purchase_price_currency' => ['val' => isset($item['purchase_price_currency']) ? $item['purchase_price_currency'] :
                    '', 'type' => 'string'],
                'suppliers_discount' => ['val' => isset($item['suppliers_discount']) ? $item['suppliers_discount'] : '', 'type' => 'int'],
                'margin' => ['val' => isset($item['margin']) ? $item['margin'] : '', 'type' => 'int'],
                'pattern_id' => ['val' => isset($item['pattern_id']) ? $item['pattern_id'] : '', 'type' => 'int'],
                'sheet' => ['val' => $aSheet->getTitle(), 'type' => 'string'],

                'country_rus' => ['val' => isset($item['country_rus']) ? $item['country_rus'] : '', 'type' => 'string'],
                'collection_rus' => ['val' => isset($item['collection_rus']) ? $item['collection_rus'] : '', 'type' => 'string'],
                'additional_info_rus' => ['val' => isset($item['additional_info_rus']) ? $item['additional_info_rus'] : '', 'type' => 'string'],
                'texture_rus' => ['val' => isset($item['texture_rus']) ? $item['texture_rus'] : '', 'type' => 'string'],
                'layer_rus' => ['val' => isset($item['layer_rus']) ? $item['layer_rus'] : '', 'type' => 'string'],
                'installation_rus' => ['val' => isset($item['installation_rus']) ? $item['installation_rus'] : '', 'type' => 'string'],
                'surface_rus' => ['val' => isset($item['surface_rus']) ? $item['surface_rus'] : '', 'type' => 'string'],
                'units_rus' => ['val' => isset($item['units_rus']) ? $item['units_rus'] : '', 'type' => 'string'],
                'packing_type_rus' => ['val' => isset($item['packing_type_rus']) ? $item['packing_type_rus'] : '', 'type' => 'string'],
                'sell_price' => ['val' => isset($item['sell_price']) ? $item['sell_price'] : '', 'type' => 'float'],
                'image_id_A' => ['val' => isset($item['image_id_A']) ? $item['image_id_A'] : '', 'type' => 'int'],
                'image_id_B' => ['val' => isset($item['image_id_B']) ? $item['image_id_B'] : '', 'type' => 'int'],
                'image_id_V' => ['val' => isset($item['image_id_V']) ? $item['image_id_V'] : '', 'type' => 'int'],
                'grading_id' => ['val' => isset($item['grading_id']) ? $item['grading_id'] : '', 'type' => 'int'],
                'texture_id' => ['val' => isset($item['texture_id']) ? $item['texture_id'] : '', 'type' => 'int'],
                'texture2_id' => ['val' => isset($item['texture2_id']) ? $item['texture2_id'] : '', 'type' => 'int'],
                'pattern' => ['val' => isset($item['pattern']) ? $item['pattern'] : '', 'type' => 'string'],
                'pattern_rus' => ['val' => isset($item['pattern_rus']) ? $item['pattern_rus'] : '', 'type' => 'string'],
                'color_rus' => ['val' => isset($item['color_rus']) ? $item['color_rus'] : '', 'type' => 'string'],
                'construction_rus' => ['val' => isset($item['construction_rus']) ? $item['construction_rus'] : '', 'type' => 'string'],
                'amount_of_units_in_pack' => ['val' => isset($item['amount_of_units_in_pack']) ? $item['amount_of_units_in_pack'] :
                    '', 'type' => 'float'],
                'amount_of_packs_in_pack' => ['val' => isset($item['amount_of_packs_in_pack']) ? $item['amount_of_packs_in_pack'] :
                    '', 'type' => 'float'],
                'sell_price_currency' => ['val' => isset($item['sell_price_currency']) ? $item['sell_price_currency'] :
                    '', 'type' => 'string'],
                'supplier' => ['val' => isset($item['supplier']) ? $item['supplier'] : '', 'type' => 'string'],
                'visual_name' => ['val' => isset($item['visual_name']) ? $item['visual_name'] : '', 'type' => 'string'],
                'visual_name_rus' => ['val' => isset($item['visual_name_rus']) ? $item['visual_name_rus'] : '', 'type' => 'string'],
            ];

            //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
            if ($rowIndex >= 3)
                array_push($array, $baseArrayItem);
        }
    }
    return $array;
}
global $parser;
$parser = getXLS(dirname(__FILE__) . '/../catalogue.xlsx'); //извлеаем данные из XLS

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

// NEW:
// image_id_A,
// image_id_B,
// image_id_V,
// grading,
// texture_id,
// texture2_id,
// pattern,
// amount_of_units_in_pack,
// amount_of_packs_in_pack,
// sell_price_currency,
// supplier,
// visual_name

// NEW RUS:
// pattern
// grading
// color
// construction
// visual_name