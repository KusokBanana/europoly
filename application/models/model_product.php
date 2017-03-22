<?php

class ModelProduct extends Model
{
    public function __construct()
    {
        $this->connect_db();
    }

    public function getFullProductEntity($product_id)
    {
        return $this->getFirst("
            SELECT `product_id`, `sell_price`, `article`, suppliers.`name` as `supplier`, brands.`name` as 'brand', `country`, `collection`, wood.`name` as 'wood', `color`, `construction`,
                products.`name` as 'name', `additional_info`, colors.`name` as 'color_id', colors2.name as 'color2', grading.name as 'grading',
                `thickness`, `width`, `length`, `texture`, `layer`, `installation`, `surface`, constructions.name as 'construction_id',
                `units`, `packing_type`, `weight`, `amount_in_pack`, `purchase_price`, `purchase_price_currency`, `suppliers_discount`, `margin`,
                patterns.name as 'pattern', `sheet`, products.status as 'status'
            FROM products
                left join brands on products.brand_id = brands.brand_id
                left join suppliers on suppliers.supplier_id = brands.supplier_id
                left join wood on products.wood_id = wood.wood_id
                left join colors on products.color_id = colors.color_id
                left join colors as colors2 on products.color2_id = colors2.color_id
                left join grading on products.grading_id = grading.grading_id
                left join constructions on products.construction_id = constructions.construction_id
                left join patterns on products.pattern_id = patterns.pattern_id
            WHERE product_id = $product_id");
    }

    public function updateField($product_id, $field, $new_value)
    {
//        $tableName = 'products';
//        if (strpos($field, '_rus')) {
//            $tableName = 'nls_products';
//            $field = str_replace('_rus', '', $field);
//        }
        $array = explode('.', $field);
        $table = $array[0];
        $name = $array[1];
        $type = $array[2];

        if ($table == 'products') {
//            if ($type == 'id' || $type == 'float' || $type = 'int') {
//                $new_value = floatval($new_value);
//            } elseif ($type == '')
            $result = $this->update("UPDATE `products` SET `$name` = '$new_value' WHERE product_id = $product_id");
            if ($result) {
                $this->clearCache('catalogue_selects');
            }
            return $result;
        } elseif ($table == 'nls_products') {
            $row = $this->getFirst("SELECT * FROM $table WHERE product_id = $product_id");
            if ($row) {
                $result = $this->update("UPDATE $table SET `$name` = '$new_value' WHERE product_id = $product_id");
                if ($result) {
                    $this->clearCache(['catalogue_selects', 'new_product_selects', 'product_selects']);
                }
                return $result;
            } else {
                $result = $this->insert("INSERT INTO $table (`product_id`, `$name`) VALUES ($product_id, '$new_value')");
                if ($result) {
                    $this->clearCache(['catalogue_selects', 'new_product_selects', 'product_selects']);
                }
                return $result;
            }
        }
        else {
            $idName = $this->getFirst("SHOW COLUMNS FROM $table WHERE `key` = 'pri'");
            $idName = $idName['Field'];
            $row = $this->getFirst("SELECT $idName FROM $table WHERE name = '$new_value'");
            if ($row && $rowId = $row[$idName]) {
                $result = $this->update("UPDATE products SET `$name` = $rowId WHERE product_id = $product_id");
                if ($result) {
                    $this->clearCache(['catalogue_selects', 'new_product_selects', 'product_selects']);
                }
                return $result;
            } else {
                $newRowId = $this->insert("INSERT INTO $table (name) VALUES ('$new_value')");
                if ($newRowId) {
                    $result = $this->update("UPDATE products SET `$name` = $newRowId WHERE product_id = $product_id");
                    if ($result) {
                        $this->clearCache(['catalogue_selects', 'new_product_selects', 'product_selects']);
                    }
                    return $result;
                }
            }
        }
    }

    public function deleteProduct($product_id)
    {
        $result = $this->delete("DELETE FROM products WHERE product_id = $product_id");
        if ($result) {
            $this->delete("DELETE FROM nls_products WHERE product_id = $product_id");
            $this->clearCache(['catalogue_selects', 'new_product_selects', 'product_selects']);
        }
        return $result;
    }

    public function getPhotos($product_id)
    {
        return $this->getAssoc("SELECT photos.photo_id AS 'photo_id', CONCAT('/images/', photos.name, '.jpg') AS 'path' 
                FROM photos JOIN products ON photos.product_id = products.product_id
                WHERE photos.product_id = $product_id");
    }

    public function addPhoto($product_id, $name)
    {
        return $this->insert("INSERT INTO photos (`product_id`, `name`, `description`) VALUES ($product_id, '$name', 'artificial')");
    }

    public function deletePhoto($photo_id)
    {
        return $this->delete("DELETE FROM photos WHERE photo_id = $photo_id");
    }

    public function getBalances($product_id) // TODO fix it
    {
        return $this->getAssoc("SELECT pw.warehouse_id as 'warehouse_id', w.name as 'name', CONCAT(SUM(pw.amount), ' ', p.units) as 'amount', CONCAT(SUM(pw.total_price), ' ', p.purchase_price_currency) as 'total_price'
                FROM `order_items` pw JOIN `products` p ON pw.product_id = p.product_id JOIN `warehouses` w ON pw.warehouse_id = w.warehouse_id
                WHERE p.product_id = $product_id
                GROUP BY pw.product_id, pw.warehouse_id
                ORDER BY pw.warehouse_id ASC");
    }

    public function getAllWarehousesBalance($product_id)
    {
        return $this->getFirst("SELECT CONCAT(SUM(pw.amount), ' ', p.units) as 'amount', CONCAT(SUM(pw.total_price), ' ', p.purchase_price_currency) as 'total_price'
                FROM `products_warehouses` pw JOIN `products` p ON pw.product_id = p.product_id
                WHERE p.product_id = $product_id
                GROUP BY pw.product_id");
    }

    public function getRus($product_id)
    {
        return $this->getFirst("SELECT * FROM nls_products WHERE product_id = $product_id");
    }

    public function getSelects()
    {
        $maximum = 80;
        $fromOtherTable = [];
        foreach ($this->columns as $name => $column) {
            $tableName = $column['table'];
            if ($tableName != 'products' && $tableName != 'nls_products') {
                $fromOtherTable[$name] = $tableName;
            }
        }
        $columnNames = array_keys($this->columns);

        $products = $this->getAssoc("SELECT * FROM products");
        $selects = [];
        foreach ($products as $product) {
            foreach ($product as $key => $value) {
                if (!$value || $value == null || !in_array($key, $columnNames) || isset($fromOtherTable[$key]))
                    continue;
                $selects[$key][] = $value;
            }
        }
        foreach ($selects as $key1 => $select) {
            $selects[$key1] = array_unique($select);

            $selectItem = [];
            $add = 0;
            foreach ($selects[$key1] as $key2 => $value) {
                $selectItem[] = ['id' => $value, 'text' => $value];
                $add++;
                if ($maximum < $add)
                    break;
            }
            $selects[$key1] = $selectItem;
        }

        foreach ($fromOtherTable as $name => $table) {
            $idName = $this->getFirst("SHOW COLUMNS FROM $table WHERE `key` = 'pri'");
            $idName = $idName['Field'];
            $otherValues = $this->getAssoc("SELECT * FROM $table");
            if (!isset($selects[$name]))
                $selects[$name] = [];
            foreach ($otherValues as $values) {
                $array = [
                    'id' => $values[$idName],
                    'text' => $values['name']
                ];
                $selects[$name][] = $array;
            }
        }
        return $selects;
    }

    var $columns = [
        'article' => ['label' => "Article", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'name' => ['label' => "Name/ Wood", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'name_rus' => ['label' => "Name RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'brand_id' => ['label' => "Brand", 'table' => 'brands', 'type' => 'id', 'isSelect' => true],
        'category_id' => ['label' => "Category (numbered)", 'table' => 'category', 'type' => 'id', 'isSelect' => true],
        'country' => ['label' => "Country", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'country_rus' => ['label' => "Country RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'collection' => ['label' => "Collection", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'collection_rus' => ['label' => "Collection RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'wood_id' => ['label' => "Wood (numbered)", 'table' => 'wood', 'type' => 'id', 'isSelect' => true],
        'additional_info' => ['label' => "Additional characteristics", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'additional_info_rus' => ['label' => "Additional characteristics RUS", 'table' => 'products', 'type' => 'string', 'isSelect' => false],
        'color_id' => ['label' => "Color 1 (numbered)", 'table' => 'colors', 'type' => 'id', 'isSelect' => true],
        'color2_id' => ['label' => "Color 2 (numbered)", 'table' => 'colors', 'type' => 'id', 'isSelect' => true],
        'color' => ['label' => "Color", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'color_rus' => ['label' => "Color RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => true],
        'grading_id' => ['label' => "Grading (numbered)", 'table' => 'grading', 'type' => 'id', 'isSelect' => true],
        'thickness' => ['label' => "Thickness", 'table' => 'products', 'type' => 'int', 'isSelect' => false],
        'width' => ['label' => "Width", 'table' => 'products', 'type' => 'string', 'isSelect' => false],
        'length' => ['label' => "Length", 'table' => 'products', 'type' => 'string', 'isSelect' => false],
        'construction_id' => ['label' => "Construction (numbered)", 'table' => 'constructions', 'type' => 'id', 'isSelect' => true],
        'construction' => ['label' => "Construction", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'construction_rus' => ['label' => "Construction RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'texture' => ['label' => "Texture", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'texture_rus' => ['label' => "Texture RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'layer' => ['label' => "Bottom layer/ Middle layer (for Admonter panels)", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'layer_rus' => ['label' => "Bottom layer/ Middle layer (for Admonter panels) RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'installation' => ['label' => "Installation", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'installation_rus' => ['label' => "Installation RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'surface' => ['label' => "Surface", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'surface_rus' => ['label' => "Surface RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'units' => ['label' => "Units", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'units_rus' => ['label' => "Units RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'sell_price' => ['label' => "Retail Price", 'table' => 'products', 'type' => 'float', 'isSelect' => false],
        'purchase_price' => ['label' => "Purchase Price", 'table' => 'products', 'type' => 'float', 'isSelect' => false],
        'purchase_price_currency' => ['label' => "Purchase Currency", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'packing_type' => ['label' => "Packing Type", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'packing_type_rus' => ['label' => "Packing Type RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
        'weight' => ['label' => "Weight of 1 unit", 'table' => 'products', 'type' => 'float', 'isSelect' => false],
        'amount_in_pack' => ['label' => "Quantity of product in 1 pack (in units)", 'table' => 'products', 'type' => 'float', 'isSelect' => false],
        'suppliers_discount' => ['label' => "Supplier's Discount", 'table' => 'products', 'type' => 'int', 'isSelect' => false],
        'margin' => ['label' => "Margin", 'table' => 'products', 'type' => 'int', 'isSelect' => false],
        'pattern_id' => ['label' => "Pattern (numbered)", 'table' => 'patterns', 'type' => 'id', 'isSelect' => true],
        'grading' => ['label' => "Grading (numbered)", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'grading_rus' => ['label' => "Grading RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => true],
//        'texture_id' => ['label' => "Surface Texture", 'table' => 'products', 'type' => 'id', 'isSelect' => true],
//        'texture2_id' => ['label' => "Surface Texture 2", 'table' => 'products', 'type' => 'id', 'isSelect' => true],
        'pattern' => ['label' => "Pattern", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'pattern_rus' => ['label' => "Pattern RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => true],
        'amount_of_units_in_pack' => ['label' => "Q-ty of units in 1 pc.", 'table' => 'products', 'type' => 'float', 'isSelect' => false],
        'amount_of_packs_in_pack' => ['label' => "Number of pcs in 1 pack", 'table' => 'products', 'type' => 'float', 'isSelect' => false],
        'sell_price_currency' => ['label' => "Retail Currency", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'supplier' => ['label' => "Supplier", 'table' => 'products', 'type' => 'string', 'isSelect' => true],
        'visual_name' => ['label' => "Visual Name", 'table' => 'products', 'type' => 'string', 'isSelect' => false],
        'visual_name_rus' => ['label' => "Visual Name RUS", 'table' => 'nls_products', 'type' => 'string', 'isSelect' => false],
    ];

}