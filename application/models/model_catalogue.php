<?php

class ModelCatalogue extends Model
{
    public function __construct()
    {
        $this->connect_db();
    }

    function getDTProducts($input, $id)
    {
        $where = '';
        $this->sspComplex($this->full_products_table, "product_id", $this->full_product_columns, $input, null, $where);

    }

    public function getAjaxColumn($colId)
    {

        $columns = $this->full_product_columns;

        if (isset($columns[$colId])) {
            $string = htmlspecialchars($columns[$colId]['db']);
            $return = [];

            if (($pos = strpos($string, 'IFNULL')) !== -1) {
                $string = substr($string, $pos);
            }

            if (preg_match("/\\w+\\.\\w+/", $string, $match)) {
                $arr = explode('.', $match[0]);
                $tableName = $arr[0];
                $name = $arr[1];

                $gets = $this->getAssoc("SELECT DISTINCT `$name` FROM `$tableName` ORDER BY `$name` ASC");
                foreach ($gets as $get) {
                    if (count($return) < 100)
                        $return[] = $get[$name];
                }
                foreach ($return as $item) {

                }
            }
            return json_encode($return);
        }

        return false;

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
        if (substr($names, -1) == ',')
            $names = substr($names, 0, -1);
        if (substr($values, -1) == ',')
            $values = substr($values, 0, -1);

        $productId = $this->insert("INSERT INTO products ($names)
                          VALUES ($values)");
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

    }

    public function getSelects()
    {
        $products = $this->getAssoc("SELECT * FROM products");
        $selects = [];
        foreach ($products as $product) {
            foreach ($product as $key => $value) {
                if (!$value || $value == null)
                    continue;
                if (in_array($key, ['product_id', 'article', 'name', 'width', 'length', 'weight',
                    'amount_in_pack', 'purchase_price', '	dealer_price', 'currency', 'suppliers_discount', 'margin',
                    'status', 'sell_price', 'thickness']))
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

}