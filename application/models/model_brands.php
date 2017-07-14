<?php

class ModelBrands extends Model
{
    var $tableName = 'table_brands';
    var $page = '';

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

    function getSSPData($type = 'general')
    {
        $ssp = ['page' => $this->page];
        switch ($type) {
            case 'general':
                if ($this->user->role_id == ROLE_SALES_MANAGER) {
                    unset($this->columns[3]);
                    unset($this->columnNames[3]);
                }
                $ssp['columns'] = $this->getColumns($this->columns, $this->page, $this->tableName);
                $ssp['columns_names'] = $this->getColumns($this->columnNames, $this->page,
                    $this->tableName, true);
                $ssp['db_table'] = 'brands left join suppliers on suppliers.supplier_id = brands.supplier_id';
                $ssp['table_name'] = $this->tableName;
                $ssp['primary'] = 'brands.brand_id';
                break;
        }

        $ssp['where'] = "brands.is_deleted = 0";
        return $ssp;
    }

    function getDTBrands($input)
    {

        $ssp = $this->getSSPData();
        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, $ssp['where']);
    }

    public function getTableData($type = 'general')
    {
        $data = $this->getSSPData($type);
        $roles = new Roles();
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            unset($this->columnNames[3]);
        }
        $data['originalColumns'] = $roles->returnModelNames($this->columnNames, $this->page);
        return $data;
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