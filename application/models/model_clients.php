<?php

class ModelClients extends Model
{
    var $client_columns = [
        array('dt' => 0, 'db' => "clients.client_id"),
        array('dt' => 1, 'db' => "clients.name"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sales_manager\" data-pk=\"', clients.client_id ,'\" data-name=\"sales_manager_id\" data-value=\"', clients.sales_manager_id, '\" data-url=\"/clients/change_item_field\" data-original-title=\"Change Manager\">', managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=', clients.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_agent\" data-pk=\"', clients.client_id ,'\" data-name=\"commission_agent_id\" data-value=\"', IFNULL(clients.commission_agent_id, ''), '\" data-url=\"/clients/change_item_field\" data-original-title=\"Change Commission Agent\">', IFNULL(commission_agents.name, ''), '</a>')"),
        array('dt' => 4, 'db' => "clients.city"),
        array('dt' => 5, 'db' => "clients.turnover"),
        array('dt' => 6, 'db' => "clients.profit"),
        array('dt' => 7, 'db' => "clients.discount_rate"),
        array('dt' => 8, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-type\" data-pk=\"', clients.client_id ,'\" data-name=\"type\" data-value=\"', clients.type, '\" data-url=\"/clients/change_item_field\" data-original-title=\"Change Manager\">', clients.type, '</a>')"),
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
        'Change Type'
    ];

    var $client_table = 'clients
            left join users as managers on clients.sales_manager_id = managers.user_id
            left join clients as commission_agents on clients.commission_agent_id = commission_agents.client_id
            left join countries on clients.country_id = countries.country_id
            left join regions on clients.region_id = regions.region_id';

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTClients($input)
    {

        $columns = $this->client_columns;

        array_push($columns,
            array('dt' => 9, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/contractors/delete?id=', clients.client_id, '&type=clients', 
                        '\" onclick=\"return confirm(\'Are you sure to delete the client?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
        );


        $this->sspComplex($this->client_table, "clients.client_id", $columns, $input, null, 'clients.is_deleted = 0');
    }

    function getDTEndCustomers($input)
    {
        $this->sspComplex($this->client_table, "clients.client_id", $this->client_columns, $input, null, "clients.type = 'End Customer' AND clients.is_deleted = 0");
    }

    function getDTCommissionAgents($input)
    {
        $this->sspComplex($this->client_table, "clients.client_id", $this->client_columns, $input, null, "clients.type = 'Commission Agent' AND clients.is_deleted = 0");
    }

    function getDTDealers($input)
    {
        $this->sspComplex($this->client_table, "clients.client_id", $this->client_columns, $input, null, "clients.type = 'Dealer' AND clients.is_deleted = 0");
    }

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
}