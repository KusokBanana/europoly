<?php

class ModelCatalogue extends Model
{
    public function __construct()
    {
        $this->connect_db();
    }

    public $tableName = "table_catalogue";

    function getDTProducts($input, $printOpt, $page = false, $table = false)
    {
        $where = ['products.is_deleted = 0'];

        $table = ($table) ? $table : $this->tableName;
        $page = ($page) ? $page : 'catalogue';
        $columns = $this->getColumns($this->full_product_columns, $page, $table);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->full_product_column_names,
            'db_table' => $this->full_products_table,
            'page' => $page,
            'table_name' => $table,
            'primary' => 'products.product_id',
        ];

        if ($printOpt) {

            $printOpt['where'] = $where;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;

        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $where);
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

    function getSelects()
    {
        $role = new Roles();
        $cols = $role->returnModelColumns($this->full_product_columns, 'catalogue');
        $ssp = $this->getSspComplexJson($this->full_products_table, "product_id", $cols, 'products.is_deleted = 0');
        $columns = $role->returnModelNames($this->full_product_column_names, 'catalogue');
        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['_product_id', 'Name', 'Article', 'Thickness', 'Width', 'Length',
            'Weight', 'Quantity in 1 Pack', 'Purchase price', 'Supplier\'s discount',
            'Margin', 'Sell',/* TODO */ 'image_id_A', 'image_id_B', 'image_id_V', 'amount_of_units_in_pack',
            'Visual Name', 'visual_name', 'amount_of_packs_in_pack'];

        if (!empty($rowValues)) {
            $selects = [];
            foreach ($rowValues as $product) {
                foreach ($product as $key => $value) {
                    if (!$value || $value == null)
                        continue;
                    $name = $columns[$key];
                    if (in_array($name, $ignoreArray))
                        continue;

                    preg_match('/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i', $value, $match);
                    if (!empty($match) && isset($match[1])) {
                        $value = $match[1];
                    }

                    if ((isset($selects[$name]) && !in_array($value, $selects[$name])) || !isset($selects[$name]))
                        $selects[$name][] = $value;
                }
            }
            return ['selects' => $selects, 'rows' => $rowValues];
        }
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