<?php

class ModelClients extends Model
{
    var $client_columns = [
        array('dt' => 0, 'db' => "clients.client_id"),
        array('dt' => 1, 'db' => "clients.name"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sales_manager\" data-pk=\"', 
            clients.client_id ,'\" data-name=\"sales_manager_id\" data-value=\"', clients.sales_manager_id, '\"
            data-url=\"/clients/change_item_field\" data-original-title=\"Change Manager\">', managers.first_name, ' ', 
            managers.last_name, '</a><a href=\"/sales_manager?id=', clients.sales_manager_id, '\">
            <i class=\"glyphicon glyphicon-link\"></i></a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_agent\" data-pk=\"', clients.client_id ,'\" data-name=\"commission_agent_id\" data-value=\"', IFNULL(clients.commission_agent_id, ''), '\" data-url=\"/clients/change_item_field\" data-original-title=\"Change Commission Agent\">', IFNULL(commission_agents.name, ''), '</a>')"),
        array('dt' => 4, 'db' => "clients.city"),
        array('dt' => 5, 'db' => "clients.turnover"),
        array('dt' => 6, 'db' => "clients.profit"),
        array('dt' => 7, 'db' => "clients.discount_rate"),
        array('dt' => 8, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-type\" data-pk=\"', clients.client_id ,'\" data-name=\"type\" data-value=\"', clients.type, '\" data-url=\"/clients/change_item_field\" data-original-title=\"Change Manager\">', clients.type, '</a>')"),
        array('dt' => 9, 'db' => "clients.email"),
        array('dt' => 10, 'db' => "clients.mobile_number"),
        array('dt' => 11, 'db' => "clients.client_category"),
        array('dt' => 12, 'db' => "clients.inn"),
        array('dt' => 13, 'db' => "clients.source"),
        array('dt' => 14, 'db' => "clients.legal_address"),
        array('dt' => 15, 'db' => "clients.actual_address"),
        array('dt' => 16, 'db' => "clients.status"),
        array('dt' => 17, 'db' => "CONCAT('<a href=\"/client?id=', clients.head_contractor_client_id, '\"\">', 
            head_contractor.name, '</a>')"),
        array('dt' => 18, 'db' => "clients.first_contact_date"),
        array('dt' => 19, 'db' => "CONCAT(operational_manager.first_name, ' ', operational_manager.last_name)"),
        array('dt' => 20, 'db' => "clients.quantity_of_people"),
        array('dt' => 21, 'db' => "clients.main_target"),
        array('dt' => 22, 'db' => "clients.showrooms"),
        array('dt' => 23, 'db' => "clients.main_competiter"),
        array('dt' => 24, 'db' => "clients.samples_position"),
        array('dt' => 25, 'db' => "clients.needful_actions"),
        array('dt' => 26, 'db' => "clients.comments"),
        array('dt' => 27, 'db' => "clients.type"),

    ];

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
        '_type'
    ];

    var $client_table = 'clients
            left join users as managers on clients.sales_manager_id = managers.user_id
            left join clients as commission_agents on clients.commission_agent_id = commission_agents.client_id
            left join countries on clients.country_id = countries.country_id
            left join clients as head_contractor on clients.head_contractor_client_id = head_contractor.client_id 
            left join users as operational_manager on clients.operational_manager_id = operational_manager.user_id 
            left join regions on clients.region_id = regions.region_id';

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTClients($input)
    {

        $columns = $this->client_columns;

        array_push($columns,
            array('dt' => 28, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the client?\" 
                                   href=\"/contractors/delete?id=', clients.client_id, '&type=clients', '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
        );
        $this->unLinkStrings($columns, [17]);
        $columns = $this->getColumns($columns, 'contractors', 'table_clients');

        $this->sspComplex($this->client_table, "clients.client_id", $columns, $input, null, 'clients.is_deleted = 0');
    }

    function getDTAllClients($input)
    {
        $where = "clients.is_deleted = 0";
        $userId = $_SESSION['user_id'];
        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
            $where .= " AND (clients.sales_manager_id = " . $userId;
            $where .=  " OR clients.operational_manager_id = " . $userId . ')';
            $this->unLinkStrings($this->client_columns, [17]);
        }

        $columns = $this->getColumns($this->client_columns, 'clients', 'table_clients');

        $this->sspComplex($this->client_table, "clients.client_id", $columns, $input, null,
            $where);
    }

    function printTable($input, $visible, $selected = [], $filters = [])
    {
        $where = [];
        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
            $userId = $_SESSION['user_id'];
            $where = [
                'clients.is_deleted = 0',
                "(clients.sales_manager_id = " . $userId .
                " OR clients.operational_manager_id = " . $userId . ")"
            ];
            $this->unLinkStrings($this->client_columns, [17]);
        }

        $columns = $this->getColumns($this->client_columns, 'clients', 'table_clients');
        $names = $this->getColumns($this->client_column_names, 'clients', 'table_clients', true);

        if (empty($selected)) {

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
            $ssp = $this->getSspComplexJson($this->client_table, "client_id", $columns, $input, null, $where);
            $values = json_decode($ssp, true)['data'];
        } else {
            $values = $selected;
        }

        require_once dirname(__FILE__) . '/../classes/Excel.php';
        $excel = new Excel();

        $data = array_merge([$names], $values);
        return $excel->printTable($data, $visible, 'clients');

    }

//    function getDTEndCustomers($input)
//    {
//        $where = "clients.type = '".CLIENT_TYPE_END_CUSTOMER."' AND clients.is_deleted = 0";
//        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
//            $where .= " AND clients.sales_manager_id = " . $_SESSION['user_id'];
//            $this->unLinkStrings($this->client_columns, [17]);
//        }
//
//        $columns = $this->getColumns($this->client_columns, 'clients', 'table_clients');
//
//        $this->sspComplex($this->client_table, "clients.client_id", $columns, $input, null,
//            $where);
//    }
//
//    function getDTCommissionAgents($input)
//    {
//        $where = "clients.type = '".CLIENT_TYPE_COMISSION_AGENT."' AND clients.is_deleted = 0";
//        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
//            $where .= " AND clients.sales_manager_id = " . $_SESSION['user_id'];
//            $this->unLinkStrings($this->client_columns, [17]);
//        }
//
//        $columns = $this->getColumns($this->client_columns, 'clients', 'table_clients');
//
//        $this->sspComplex($this->client_table, "clients.client_id", $columns, $input, null,
//            $where);
//    }
//
//    function getDTDealers($input)
//    {
//        $where = "clients.type = '".CLIENT_TYPE_DEALER."' AND clients.is_deleted = 0";
//        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
//            $where .= " AND clients.sales_manager_id = " . $_SESSION['user_id'];
//            $this->unLinkStrings($this->client_columns, [17]);
//        }
//
//        $columns = $this->getColumns($this->client_columns, 'clients', 'table_clients');
//
//        $this->sspComplex($this->client_table, "clients.client_id", $columns, $input, null, $where);
//    }

    function addClient($name, $type, $manager_id, $commission_agent_id, $country_id, $region_id, $city)
    {
        return $this->insert("INSERT INTO `clients` (`name`, `type`, sales_manager_id, `commission_agent_id`, `country_id`, `region_id`, `city`) 
            VALUES ('$name', '$type', $manager_id, $commission_agent_id, $country_id, $region_id, '$city')");
    }

    public function updateItemField($client_id, $field, $new_value)
    {
        $result = $this->update("UPDATE `clients` SET `$field` = '$new_value' WHERE client_id = $client_id");
        return $result;
    }

    public function getSelects()
    {
        $where = "clients.is_deleted = 0";
        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
            $userId = $_SESSION['user_id'];
            $where .= " AND (clients.sales_manager_id = " . $userId . " OR clients.operational_manager_id = " . $userId . ")";
            $this->unLinkStrings($this->client_columns, [17]);
        }
        $role = new Roles();
        $cols = $role->returnModelColumns($this->client_columns, 'clients');
        $columns = $role->returnModelNames($this->client_column_names, 'clients');

        $ssp = $this->getSspComplexJson($this->client_table, "clients.client_id", $cols, null, null,
            $where);

        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['_client_id', 'Turnover', 'Profit', 'Discount Rate', 'Change Type'];

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