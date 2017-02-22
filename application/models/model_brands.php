<?php

class ModelBrands extends Model
{
    var $tableName = 'table_brands';

    var $columns = array(
        array('dt' => 0, 'db' => 'brands.brand_id'),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', brands.name, '</a>')"),
        array('dt' => 2, 'db' => 'suppliers.name'),
        array('dt' => 3, 'db' => '"N/A"')
    );

    var $columnNames = array(
        '_brand_id',
        'Name',
        'Supplier',
        'Status'
    );

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTBrands($input)
    {
        $this->sspSimple('brands left join suppliers on suppliers.supplier_id = brands.supplier_id', "brand_id", $this->columns, $input);
    }

    function addBrand($name, $supplier)
    {

        $supplierFind = $this->getFirst("SELECT supplier_id FROM suppliers WHERE name = '$supplier'");
        if ($supplierFind)
            $supplier = $supplierFind['supplier_id'];
        else {
            $newSupplier = $this->insert("INSERT INTO `suppliers`(`name`)
            VALUES ('$supplier')");
            $supplier = $newSupplier;
        }

        return $this->insert("
            INSERT INTO `brands`(`name`, `supplier_id`)
            VALUES ('$name', '$supplier')");
    }
    function getSuppliersIdNames()
    {
        return $this->getAssoc("SELECT supplier_id as id, name FROM suppliers WHERE is_deleted = 0");
    }
}