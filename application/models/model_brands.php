<?php

class ModelBrands extends Model
{
    var $tableName = 'table_brands';

    var $columns = array(
        array('dt' => 0, 'db' => 'brands.brand_id'),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', brands.name, '</a>')"),
        array('dt' => 2, 'db' => 'suppliers.name'),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/brands/delete?id=', brands.brand_id,
                                '\" class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                 data-title=\"Are you sure to delete the item?\" data-toggle=\"confirmation\"
                                 data-id=\"', brands.brand_id, '\" data-singleton=\"true\">
                                    <span class=\'glyphicon glyphicon-trash\' title=\'Delete?\'></span>
                                </a>')")
    );

    var $columnNames = array(
        '_brand_id',
        'Name',
        'Supplier',
        'Delete'
    );

    public function __construct()
    {
        $this->connect_db();
    }

    function getColumnNames()
    {
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            unset($this->columnNames[3]);
        }
        return $this->columnNames;
    }

    function getDTBrands($input)
    {

        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            unset($this->columns[3]);
        }

        $where = "brands.is_deleted = 0";
        $this->sspComplex('brands left join suppliers on suppliers.supplier_id = brands.supplier_id', "brand_id",
            $this->columns, $input, $where);
    }

    function addBrand($name, $supplier_id)
    {

//        $supplierFind = $this->getFirst("SELECT supplier_id FROM suppliers WHERE supplier_id = $supplier_id");
//        if ($supplierFind)
//            $supplier = $supplierFind['supplier_id'];
//        else {
//            $newSupplier = $this->insert("INSERT INTO `suppliers`(`name`)
//            VALUES ('$supplier')");
//            $supplier = $newSupplier;
//        }

        return $this->insert("
            INSERT INTO `brands`(`name`, `supplier_id`)
            VALUES ('$name', $supplier_id)");
    }
    function getSuppliersIdNames()
    {
        return $this->getAssoc("SELECT supplier_id as id, name FROM suppliers WHERE is_deleted = 0");
    }

    function deleteBrand($brandId)
    {
        $this->update("UPDATE brands SET is_deleted = 1 WHERE brand_id = $brandId");
    }

}