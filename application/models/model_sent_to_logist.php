<?php
include_once 'model_managers_orders.php';

class ModelSent_to_logist extends ModelManagers_orders
{

    public $tableNames = ['sent_to_logist'];
    public $page = '';

    var $whereCondition = 'order_items.manager_order_id IS NOT NULL AND '.
    'order_items.is_deleted = 0 AND order_items.status_id = ' . SENT_TO_LOSIGT;

    function getDTManagersOrders($input, $printOpt, $ids = false)
    {

        $ssp = $this->getTableData();
        $where = [$this->whereCondition];

        if ($ids !== false) {
            $where[] = "order_items.item_id IN ($ids)";
        }

        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $where[] = "(orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                "client.sales_manager_id = " . $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id .
                ' OR order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
            $this->unLinkStrings($this->managers_orders_columns, [24, 25]);
        }

        $ssp['where'] = $where;

        if ($printOpt) {
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $ssp['where']);

    }

    /**
     * @param string $type
     * @return array = ['columns', 'columns_names', 'db_table', 'table_name', 'primary', 'page', 'originalColumns',
     *                      'selectSearch', 'filterSearchValues']
     */
    public function getTableData($type = 'general')
    {
        $data = $this->getSSPData($type);

        $where = [$this->whereCondition];
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $where[] = "(orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                "client.sales_manager_id = " . $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id .
                ' OR order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
            $this->unLinkStrings($this->managers_orders_columns, [24, 25]);
        }

        $data['where'] = $where;

        switch ($type) {
            case 'general':
                $selects = $this->getSelects($data);
        }

        return array_merge($data, $selects);
    }


}
