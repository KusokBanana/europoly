<?php
include_once "model_catalogue.php";

class ModelBrand extends ModelCatalogue
{

    public $tableName = "table_brand_products";

    function getDTProductsForBrand($brand_id, $input)
    {
        $where = "brands.brand_id = " . $brand_id;

        $columns = $this->getColumns($this->full_product_columns, 'brand', $this->tableName);

        $this->sspComplex($this->full_products_table, "product_id",
            $columns, $input, null, $where);
    }

    function printTable($input, $visible, $selected = [], $filters = [])
    {

        $columns = $this->getColumns($this->full_product_columns, 'brand', $this->tableName);

        $names = $this->getColumns($this->full_product_column_names, 'brand', $this->tableName, true);
        if (empty($selected)) {
            $brand_id = $_POST["products"]['brand_id'];
            $where = ["brands.brand_id = " . $brand_id];
            if (!empty($filters)) {
                foreach ($filters as $colId => $value) {
                    if (!$value || $value == null)
                        continue;

                    if (is_int($value))
                        $where[] = $columns[$colId]['db'] . ' = ' . $value;
                    elseif (is_string($value))
                        $where[] = $columns[$colId]['db'] . " LIKE '%$value%'";
                }
            }
            $where = join(' AND ', $where);
            $ssp = $this->getSspComplexJson($this->full_products_table, "product_id",
                $columns, $input, null, $where);
            $values = json_decode($ssp, true)['data'];
        } else {
            $values = $selected;
        }

        require_once dirname(__FILE__) . '/../classes/Excel.php';
        $excel = new Excel();

        $data = array_merge([$names], $values);
        return $excel->printTable($data, $visible, 'brand');

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