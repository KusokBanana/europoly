<?php

class ModelCatalogue extends Model
{
    public function __construct()
    {
        $this->connect_db();
    }

    public $tableName = "table_catalogue";
    public $page = "";
    public $whereCondition = ['products.is_deleted = 0'];

    function getSSPData($type = 'general')
    {
        $ssp = ['page' => $this->page];
        switch ($type) {
            case 'general':
                $ssp = array_merge($ssp, $this->getColumns($this->full_product_columns, $this->page, $this->tableName));
                $ssp = array_merge($ssp, $this->getColumns($this->full_product_column_names, $this->page,
                    $this->tableName, true));
                $ssp['db_table'] = $this->full_products_table;
                $ssp['table_name'] = $this->tableName;
                $ssp['primary'] = 'products.product_id';
                $ssp['hidden_by_default'] = $this->full_product_hidden_columns;
                break;
        }

        $ssp['where'] = $this->whereCondition;

        return $ssp;
    }

    function getDTProducts($input, $printOpt, $table = false, $page = false)
    {
        $this->tableName = ($table) ? $table : $this->tableName;
        $this->page = ($page) ? $page : $this->page;

        $ssp = $this->getSSPData();

        if ($printOpt) {
            $printOpt['where'] = $ssp['where'];
            echo $this->printTable($input, $ssp, $printOpt); // TODO refactor this - merge printOpt and ssp
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $ssp['where']);
    }

    public function newProductSelects()
    {
        $products = $this->getAssoc("SELECT * FROM products WHERE is_deleted = 0");
        $selects = [];
        foreach ($products as $product) {
            foreach ($product as $key => $value) {
                if (!$value || $value == null)
                    continue;
                if (in_array($key, ['product_id', 'article', 'width', 'length', 'weight',
                    'amount_in_pack', 'purchase_price', 'dealer_price', 'purchase_price_currency', 'suppliers_discount', 'margin',
                    'sell_price', 'thickness', 'image_id_A', 'image_id_B', 'image_id_V', 'amount_of_units_in_pack',
                    'visual_name', 'amount_of_packs_in_pack']))
                    continue;
                $selects[$key][] = $value;
            }
        }
        foreach ($selects as $key1 => $select) {
            $selects[$key1] = array_unique($select);

            $selectItem = [];
            foreach ($selects[$key1] as $key2 => $value) {
                $selectItem[] = ['id' => $value, 'text' => $value];
            }
            $selects[$key1] = $selectItem;
        }

        return $selects;
    }

    function getSelects($ssp)
    {
        $sspJson = $this->getSspComplexJson($ssp['db_table'], $ssp['primary'],
            $ssp['original_columns'], null, null, $ssp['where']);
        $rowValues = json_decode($sspJson, true)['data'];
        $columnsNames = $ssp['original_columns_names'];

        $ignoreArray = ['_product_id', 'Name', 'Article', 'Thickness', 'Width', 'Length',
            'Weight', 'Quantity in 1 Pack', 'Purchase price', 'Supplier\'s discount',
            'Margin', 'Sell',/* TODO */ 'image_id_A', 'image_id_B', 'image_id_V', 'amount_of_units_in_pack',
            'Visual Name', 'visual_name', 'amount_of_packs_in_pack'];

        if (!empty($rowValues)) {
            $selects = Helper::getSelectsFromValues($rowValues, $columnsNames, $ignoreArray);
            return ['selectSearch' => $selects, 'filterSearchValues' => $rowValues];
        }
        return [];
    }

    public function getTableData($type = 'general', $opts = [])
    {
        $data = $this->getSSPData($type);

        switch ($type) {
            case 'general':
                $cache = new Cache();
                $selects = $cache->getOrSet('catalogue_selects', function() use($data) {
                    $array = $this->getSelects($data);
                    return $array;
                });
                break;
        }

        return array_merge($data, $selects);
    }

    public function addProduct($postArray, $rusArray = [])
    {
        $names = '';
        $values = '';
        foreach ($postArray as $name => $value) {
            if ($value === 'null')
                continue;
            $arr = explode('_fix_', $name);
            if (isset($arr[1])) {
                $name = $arr[0];
                $tableName = $arr[1];
                $found = $this->getFirst("SELECT * FROM $tableName WHERE name = '$value'");
                if ($found && !empty($found)) {
                    $id = 0;
                    // For every table it's primary key named not same, so need to find the primary
                    foreach ($found as $rowKey => $rowName) {
                        if (strpos($rowKey, '_id') !== -1 && strpos($rowKey, substr($tableName, 3))  !== -1) {
                            $value = $rowName;
                            $id = $rowName;
                            break;
                        }
                    }
                    if ($id) {
                        $resource_id = intval($found['nls_resource_id']);
                        if (isset($rusArray[$name]) && $rusArray[$name] && $rusArray[$name] !== 'null') {
                            $rusName = $rusArray[$name];
                            if ($resource_id) {
                                $this->update("UPDATE nls_resources SET value = '$rusName' WHERE resource_id = $resource_id");
                            } else {
                                $maxId = $this->getMax("SELECT MAX(resource_id) FROM nls_resources");
                                $maxId++;
                                $this->insert("INSERT INTO nls_resources (`nls_resource_id`, `language_id`, `value`) 
                                            VALUES ($maxId, 2, '$rusName')");
                            }
                            unset($rusArray[$name]);
                        }
                    }
                } else {
                    $maxId = 0;
                    if (isset($rusArray[$name]) && $rusArray[$name] && $rusArray[$name] !== 'null') {
                        $maxId = $this->getMax("SELECT MAX(resource_id) FROM nls_resources") + 1;
                    }
                    $id = $this->insert("INSERT INTO $tableName (`name`, `nls_resource_id`) VALUES ('$value', $maxId)");
                    if ($id && $maxId) {
                        $rusName = $rusArray[$name];
                        $this->insert("INSERT INTO nls_resources (`nls_resource_id`, `language_id`, `value`) 
                                            VALUES ($maxId, 2, '$rusName')");
                        unset($rusArray[$name]);
                    }
                    $value = $id;
                }
            }
            $value = trim($value);
            if (!$value && !is_numeric($value))
                continue;
            $value = mysql_escape_string($value);
            $names .= $name . ', ';
            $values .= "'$value', ";
        }
        // delete ',' from end
        $names = trim($names);
        $values = trim($values);
//        if (substr($names, -1) == ',')
//            $names = substr($names, 0, -1);
//        if (substr($values, -1) == ',')
//            $values = substr($values, 0, -1);

        $productId = $this->insert("INSERT INTO products ($names change_time)
                          VALUES ($values NOW())");
        $rusNames = '';
        $rusValues = '';
        if (!empty($rusArray)) {
            foreach ($rusArray as $rusKey => $rusValue) {
                if (!$rusValue || $rusValue === 'null')
                    continue;
                $rusNames .= $rusKey . ', ';
                $rusValues .= "'$rusValue', ";
            }
        }
        if ($productId && $rusNames && $rusValues) {
            $rusNames = trim($rusNames);
            $rusValues = trim($rusValues);

            $this->insert("INSERT INTO nls_products ($rusNames language_id, product_id) 
                                            VALUES ($rusValues 2, $productId)");
        }

        if ($productId)
            $this->clearCache(['catalogue_selects', 'new_product_selects', 'product_selects']);

    }

    public function getProductValuesForSimilar($product_id)
    {
        $product = $this->getFirst("SELECT * FROM products WHERE product_id = $product_id");
        $rusProduct = $this->getFirst("SELECT * FROM nls_products WHERE product_id = $product_id");
        foreach ($rusProduct as $name => $value) {
            $newName = $name . '_rus';
            $product[$newName] = $value;
        }
        return json_encode($product);
    }


    public function getCategoryTabs()
    {
        $categories = $this->getAll('category');
        $floors = ['name' => 'Floors', 'items' => [], 'id' => []];
        $windows = ['name' => 'Windows', 'items' => [], 'id' => []];
        $interior = ['name' => 'Interior Elements', 'items' => [], 'id' => []];
        $other = ['name' => 'Other', 'items' => [], 'id' => []];
        $all = ['name' => 'All'];
        foreach ($categories as $category) {
            $catId = $category['category_id'];
            $item = [
                'id' => $catId,
                'name' => $category['name']
            ];
            switch ($catId) {
                case 1:
                case 2:
                case 4:
                case 5:
                case 6:
                    $floors['items'][] = $item;
                    break;
                case 7:
                case 13:
                case 14:
                    $interior['items'][] = $item;
                    break;
                case 3:
                case 8:
                case 9:
                case 10:
                case 11:
                    $other['items'][] = $item;
                    break;
                case 12:
                    $windows = $item;
                    break;
            }
        }
        return [$all, $floors, $windows, $interior, $other];
    }

    public function getRusValue($field, $value, $table=false)
    {

        if (!$table) {

            $productId = $this->getFirst("SELECT product_id FROM products WHERE `$field` = '$value'");
            if ($productId && $productId = $productId['product_id']) {
                $russian = $this->getFirst("SELECT * FROM nls_products WHERE product_id = $productId");
                return ($russian) ? $russian[$field] : '';
            }

        } else {

            $nls_product = $this->getFirst("SELECT * FROM `$table` WHERE '$field' => '$value'");
            if ($nls_product) {
                $nls_product_id = $nls_product['nls_product_id'];
                $russian_product = $this->getFirst("SELECT * FROM nls_resources WHERE nls_resource_id = $nls_product_id");
                return ($russian_product) ? $russian_product['value'] : '';
            }

        }


    }

}