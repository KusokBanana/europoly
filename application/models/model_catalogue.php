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

    public function addProduct($article, $name, $brand_id, $country, $collection, $wood_id, $additional_info, $color_id, $color2_id,
                               $grading_id, $thickness, $width, $length, $texture, $layer, $installation, $surface, $construction_id, $units, $packing_type,
                               $weight, $amount_in_pack, $purchase_price, $currency, $suppliers_discount, $margin, $pattern_id, $status)
    {
        return $this->insert("INSERT INTO `products`(`article`, `name`, `brand_id`, `country`, `collection`, `wood_id`,
                `additional_info`, `color_id`, `color2_id`, `grading_id`, `thickness`, `width`, `length`, `texture`, `layer`, `installation`,
                `surface`, `construction_id`, `units`, `packing_type`, `weight`, `amount_in_pack`, `purchase_price`, `currency`, `suppliers_discount`,
                `margin`, `pattern_id`, `status`) 
            VALUES ('$article', '$name', $brand_id, '$country', '$collection', $wood_id, '$additional_info', $color_id, $color2_id,
                $grading_id, $thickness, $width, $length, '$texture', '$layer', '$installation', '$surface', $construction_id, '$units',
                '$packing_type', $weight, '$amount_in_pack', '$purchase_price', '$currency', '$suppliers_discount', '$margin', $pattern_id, '$status')");
    }
}