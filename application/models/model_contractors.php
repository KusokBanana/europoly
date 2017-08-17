<?php

include_once ('model_clients.php');

class ModelContractors extends ModelClients
{

    public $page;

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
        'Email',
        'Mobile Number',
        'Category',
        'INN',
        'Source',
        'Legal Address',
        'Actual Address',
        'Status',
        'Head Contractor',
        'First Contact Date',
        'Operational Manager',
        'Quantity of People',
        'Main Target',
        'Showrooms',
        'Main Competiter',
        'Samples Position',
        'Needful Actions',
        'Comments',
        'Legal Name',
        'Type',
        'Design Buro',
        'Second Name',
//        'Client Category 2',
        'Is Agree for Formation',
        'Delete',
    ];

    function getSSPData($type = 'general')
    {

        $ssp = ['page' => $this->page];
        $tableName = 'table_'.$type;
	    $ssp['where'] = [];
	    $ssp['table_name'] = $tableName;

        switch ($type) {
            case PAYMENT_CATEGORY_CLIENT:
                $columns = $this->client_columns;
                $columns[1]['db'] = "CONCAT('<a href=\"/contractor?id=', clients.client_id, 
            '&type=', '".PAYMENT_CATEGORY_CLIENT."', '\">', clients.final_name, '</a>')";
                $count = count($columns);
                array_push($columns,
                    array('dt' => $count, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the client?\" 
                                   href=\"/contractors/delete?id=', clients.client_id, '&type=clients', '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
                );
                $this->unLinkStrings($columns, [17]);

                $ssp = array_merge($ssp, $this->getColumns($columns, $this->page,
                    $tableName));
                $ssp = array_merge($ssp, $this->getColumns($this->client_column_names, $this->page,
                    $tableName, true));
                $ssp['db_table'] = $this->client_table;
                $ssp['primary'] = 'clients.client_id';
	            break;
	        case PAYMENT_CATEGORY_SUPPLIER:
		        $ssp['db_table'] = 'suppliers';
		        $ssp['primary'] = 'suppliers.supplier_id';
		        $ssp = array_merge($ssp, $this->getColumns($this->suppliers_columns, $this->page,
			        $tableName));
		        $ssp = array_merge($ssp, $this->getColumns($this->column_names, $this->page,
			        $tableName, true));
		        break;
	        case PAYMENT_CATEGORY_CUSTOMS:
		        $ssp['db_table'] = 'customs';
		        $ssp['primary'] = 'customs.custom_id';
		        $ssp = array_merge($ssp, $this->getColumns($this->customs_columns, $this->page,
			        $tableName));
		        $ssp = array_merge($ssp, $this->getColumns($this->column_names, $this->page,
			        $tableName, true));
		        break;
	        case PAYMENT_CATEGORY_DELIVERY:
		        $ssp['db_table'] = 'transportation_companies as transport';
		        $ssp['primary'] = 'transport.transportation_company_id';
		        $ssp = array_merge($ssp, $this->getColumns($this->transportation_columns, $this->page,
			        $tableName));
		        $ssp = array_merge($ssp, $this->getColumns($this->column_names, $this->page,
			        $tableName, true));
		        break;
	        case PAYMENT_CATEGORY_OTHER:
		        $ssp['db_table'] = 'other';
		        $ssp['primary'] = 'other.other_id';
		        $ssp = array_merge($ssp, $this->getColumns($this->other_columns, $this->page,
			        $tableName));
		        $ssp = array_merge($ssp, $this->getColumns($this->column_names, $this->page,
			        $tableName, true));
		        break;
        }

        return $ssp;

    }

    public function getDTContractors($input, $type, $printOpt)
    {
        $ssp = $this->getSSPData($type);

        if ($printOpt) {
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null,
            $ssp['where']);

    }

    public function getTableData($type = 'general', $opts = [])
    {
        $data = $this->getSSPData($type);
        $selects = $this->getSelects($data);

        return array_merge($data, $selects);
    }

    var $suppliers_columns = [
            array('dt' => 0, 'db' => "suppliers.supplier_id"),
            array('dt' => 1, 'db' => "CONCAT('<a href=\"/contractor?id=', suppliers.supplier_id, 
            '&type=', '".PAYMENT_CATEGORY_SUPPLIER."', '\">', suppliers.name, '</a>')"),
            array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the supplier?\" 
                                   href=\"/contractors/delete?id=', suppliers.supplier_id, '&type=suppliers', '\"
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
    ];


    var $column_names = [
        '_id',
        'Name',
        'Delete'
    ];

    var $customs_columns = [
        array('dt' => 0, 'db' => "customs.custom_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/contractor?id=', customs.custom_id, 
            '&type=', '".PAYMENT_CATEGORY_CUSTOMS."', '\">', customs.name, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the custom?\" 
                                   href=\"/contractors/delete?id=', customs.custom_id, '&type=customs', '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
    ];

    var $transportation_columns = [
        array('dt' => 0, 'db' => "transport.transportation_company_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/contractor?id=', transport.transportation_company_id, 
            '&type=', '".PAYMENT_CATEGORY_DELIVERY."', '\">', transport.name, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the transportation company?\" 
                                   href=\"/contractors/delete?id=', transport.transportation_company_id, 
                                    '&type=transportation_companies', '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
    ];

    var $other_columns = [
        array('dt' => 0, 'db' => "other.other_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/contractor?id=', other.other_id, 
            '&type=', '".PAYMENT_CATEGORY_OTHER."', '\">', other.name, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the other?\" 
                                   href=\"/contractors/delete?id=', other.other_id, '&type=other', '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
    ];

    function addNewContractor($type, $name)
    {
        $name = Helper::safeVar($name);
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