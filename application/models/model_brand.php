<?php
include_once "model_catalogue.php";

class ModelBrand extends ModelCatalogue
{
    function getDTProductsForBrand($brand_id, $input)
    {
        $where = "brands.brand_id = " . $brand_id;

        $role = new Roles();
        $cols = $role->returnModelColumns($this->full_product_columns, 'catalogue');

        $this->sspComplex($this->full_products_table, "product_id",
            $cols, $input, null, $where);
    }

    function getSelectsBrand($brand_id)
    {
        $where = "brands.brand_id = " . $brand_id;
        $role = new Roles();
        $cols = $role->returnModelColumns($this->full_product_columns, 'catalogue');
        $ssp = $this->getSspComplexJson($this->full_products_table, "product_id", $cols, null, $where);
        $columns = $role->returnModelNames($this->full_product_column_names, 'catalogue');
        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['_product_id', 'Name', 'Article', 'Thickness', 'Width', 'Length',
            'Weight', 'Quantity in 1 Pack', 'Purchase price', 'Supplier\'s discount',
            'Margin', 'Sell'];

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
}