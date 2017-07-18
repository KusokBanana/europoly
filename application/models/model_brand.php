<?php
include_once "model_catalogue.php";

class ModelBrand extends ModelCatalogue
{

    public $tableName = "table_brand_products";
    public $page = "";

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

        $ssp['where'] = ['products.is_deleted = 0'];

        return $ssp;
    }

    function getDTProductsForBrand($brand_id, $input, $printOpt)
    {

        $ssp = $this->getSSPData();
        $ssp['where'][] = "brands.brand_id = " . $brand_id;

        if ($printOpt) {
            $printOpt['where'] = $ssp['where'];
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $ssp['where']);
    }

    public function getTableData($type = 'general', $opts = [])
    {
        $data = $this->getSSPData($type);
        $brand_id = $opts['brand_id'];
        $data['where'][] = "brands.brand_id = " . $brand_id;

        switch ($type) {
            case 'general':
                $cache = new Cache();
                $selects = $cache->getOrSet('brand_catalogue_selects' . $brand_id, function() use($data) {
                    return $this->getSelects($data);
                });
                break;
        }

        return array_merge($data, $selects);
    }
}