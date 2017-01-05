<?php

class ModelContractors extends Model
{

    public function __construct()
    {
        $this->connect_db();
    }

    var $client_column_names = [
        '_client_id',
        'Name',
        'Manager',
        'Commission Agent',
        'City',
        'Turnover',
        'Profit',
        'Discount Rate',
        'Change Type',
        'Delete',
    ];

    var $suppliers_columns = [
            array('dt' => 0, 'db' => "suppliers.supplier_id"),
            array('dt' => 1, 'db' => "suppliers.name"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/contractors/delete?id=', suppliers.supplier_id, '&type=suppliers', 
                        '\" onclick=\"return confirm(\'Are you sure to delete the supplier?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
    ];


    var $column_names = [
        '_id',
        'Name',
        'Delete'
    ];

    function getDTSuppliers($input)
    {
        $this->sspComplex('suppliers', "suppliers.supplier_id", $this->suppliers_columns, $input, null, 'suppliers.is_deleted != 1');
    }

    var $customs_columns = [
        array('dt' => 0, 'db' => "customs.custom_id"),
        array('dt' => 1, 'db' => "customs.name"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/contractors/delete?id=', customs.custom_id, '&type=customs', 
                        '\" onclick=\"return confirm(\'Are you sure to delete the custom?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
    ];

    function getDTCustoms($input)
    {
        $this->sspComplex('customs', "customs.custom_id", $this->customs_columns, $input, null, 'customs.is_deleted != 1');
    }

    var $transportation_columns = [
        array('dt' => 0, 'db' => "transport.transportation_company_id"),
        array('dt' => 1, 'db' => "transport.name"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/contractors/delete?id=', transport.transportation_company_id, '&type=transportation_companies', 
                        '\" onclick=\"return confirm(\'Are you sure to delete the transportation company?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
    ];

    function getDTTransportation($input)
    {
        $this->sspComplex('transportation_companies as transport', "transport.transportation_company_id",
            $this->transportation_columns, $input, null, 'transport.is_deleted != 1');
    }

    var $other_columns = [
        array('dt' => 0, 'db' => "other.other_id"),
        array('dt' => 1, 'db' => "other.name"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/contractors/delete?id=', other.other_id, '&type=other', 
                        '\" onclick=\"return confirm(\'Are you sure to delete the other?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
    ];

    function getDTOther($input)
    {
        $this->sspComplex('other', "other.other_id",
            $this->other_columns, $input, null, 'other.is_deleted != 1');
    }

    function addNewContractor($type, $name)
    {
        $this->insert("INSERT INTO `$type` (`name`) VALUES ('$name')");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function deleteContractor($type, $id)
    {

        $idName = '';
        switch ($type) {
            case 'clients':
                $idName = 'client_id';
                break;
            case 'suppliers':
                $idName = 'supplier_id';
                break;
            case 'transportation_companies':
                $idName = 'transportation_company_id';
                break;
            case 'other':
                $idName = 'other_id';
                break;
            case 'customs':
                $idName = 'custom_id';
                break;
        }

        $this->update("UPDATE `$type` 
                              SET `is_deleted` = 1 WHERE `$idName` = $id");

    }


}