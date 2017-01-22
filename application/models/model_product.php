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
                `units`, `packing_type`, `weight`, `amount_in_pack`, `purchase_price`, `currency`, `suppliers_discount`, `margin`,
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
        $tableName = 'products';
        if (strpos($field, '_rus')) {
            $tableName = 'nls_products';
            $field = str_replace('_rus', '', $field);
        }

        return $this->update("UPDATE `$tableName` SET `$field` = '$new_value' WHERE product_id = $product_id");
    }

    public function deleteProduct($product_id)
    {
        return $this->delete("DELETE FROM products WHERE product_id = $product_id");
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

    public function getBalances($product_id)
    {
        return $this->getAssoc("SELECT pw.warehouse_id as 'warehouse_id', w.name as 'name', CONCAT(SUM(pw.amount), ' ', p.units) as 'amount', CONCAT(SUM(pw.total_price), ' ', p.currency) as 'total_price'
                FROM `products_warehouses` pw JOIN `products` p ON pw.product_id = p.product_id JOIN `warehouses` w ON pw.warehouse_id = w.warehouse_id
                WHERE p.product_id = $product_id
                GROUP BY pw.product_id, pw.warehouse_id
                ORDER BY pw.warehouse_id ASC");
    }

    public function getAllWarehousesBalance($product_id)
    {
        return $this->getFirst("SELECT CONCAT(SUM(pw.amount), ' ', p.units) as 'amount', CONCAT(SUM(pw.total_price), ' ', p.currency) as 'total_price'
                FROM `products_warehouses` pw JOIN `products` p ON pw.product_id = p.product_id
                WHERE p.product_id = $product_id
                GROUP BY pw.product_id");
    }

    public function getRus($product_id)
    {
        return $this->getFirst("SELECT * FROM nls_products WHERE product_id = $product_id");
    }
}