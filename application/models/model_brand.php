<?php

class ModelBrand extends Model
{
    public function __construct()
    {
        $this->connect_db();
    }

    function getDTProductsForBrand($brand_id, $input)
    {
        $where = "brands.brand_id = " . $brand_id;


        $this->sspComplex($this->full_products_table, "product_id",
            $this->full_product_columns, $input, null, $where);
    }
}