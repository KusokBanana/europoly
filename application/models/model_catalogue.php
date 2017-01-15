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

    public function addProduct($postArray)
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
                    // For every table it's primary key named not same, so need to find the primary
                    foreach ($found as $rowKey => $rowName) {
                        if (strpos($rowKey, '_id') !== -1 && strpos($rowKey, substr($tableName, 3))  !== -1) {
                            $value = $rowName;
                            break;
                        }
                    }
                } else {
                    $id = $this->insert("INSERT INTO $tableName (`name`) VALUES ('$value')");
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

        $this->insert("INSERT INTO products ($names)
                          VALUES ($values)");

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