<?php
include_once 'model_managers_orders.php';

class ModelSent_to_logist extends ModelManagers_orders
{

    public $tableName = 'sent_to_logist';

    var $statusesFilter = 3;

    function getDTManagersOrders($input, $printOpt, $ids = false)
    {
        $where = ["order_items.status_id = '$this->statusesFilter'"];

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

        $columns = $this->getColumns($this->managers_orders_columns, 'sentToLogist', $this->tableName);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->managers_orders_column_names,
            'db_table' => $this->managers_orders_table,
            'page' => 'sentToLogist',
            'table_name' => $this->tableName,
            'primary' => 'order_items.item_id',
        ];

        if ($printOpt) {
            $printOpt['where'] = $where;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $where);

    }

    function getSelects()
    {
        $where = "(order_items.status_id = '$this->statusesFilter')";

        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
            $userId = $_SESSION['user_id'];
            $where .= " AND (orders.sales_manager_id = " . $userId . ' OR '.
                "client.sales_manager_id = " . $userId .
                " OR client.operational_manager_id = " . $userId .
                ' OR order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
            $this->unLinkStrings($this->managers_orders_columns, [24, 25]);
        }

        $columns = $this->getColumns($this->managers_orders_columns, 'sentToLogist', $this->tableName);

        $ssp = $this->getSspComplexJson($this->managers_orders_table, "orders.order_id",
            $columns, null, null, $where);
        $columnNames = $this->getColumns($this->managers_orders_column_names, 'sentToLogist', $this->tableName, true);

        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['Manager Order ID', 'Quantity', 'Number of Packs', 'Total Weight', 'Purchase Price / Unit',
            'Total Purchase Price', 'Sell Price / Unit', 'Total Sell Price', 'Downpayment', 'Downpayment rate',
            'Supplier Order ID', 'Truck ID'];

        if (!empty($rowValues)) {
            $selects = [];
            foreach ($rowValues as $product) {
                foreach ($product as $key => $value) {
                    if (!$value || $value == null)
                        continue;
                    $name = $columnNames[$key];
                    if (in_array($name, $ignoreArray))
                        continue;

                    if (strpos($value, 'glyphicon') !== false) {
                        $value = preg_replace('/<a \w+[^>]+?[^>]+>(.*?)<\/a>/i', '', $value);
                    } else {
                        preg_match('/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i', $value, $match);
                        if (!empty($match) && isset($match[1])) {
                            $value = $match[1];
                        }
                    }

                    if ((isset($selects[$name]) && !in_array($value, $selects[$name])) || !isset($selects[$name]))
                        $selects[$name][] = $value;
                }
            }
            return ['selects' => $selects, 'rows' => $rowValues];
        }
    }

}
