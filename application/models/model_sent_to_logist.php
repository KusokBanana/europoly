<?php
include_once 'model_managers_orders.php';

class ModelSent_to_logist extends ModelManagers_orders
{

    public $tableName = 'sent_to_logist';

    var $statusesFilter = 3;

    function getDTManagersOrders($input)
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

        $this->sspComplex($this->managers_orders_table, "orders.order_id",
            $columns, $input, null, $where);
    }

    function printTable($input, $visible, $selected = [], $filters = [])
    {
        $where = ["(order_items.status_id = '$this->statusesFilter')"];

        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
            $where[] = "(orders.sales_manager_id = " . $_SESSION['user_id'] . ' OR 
                order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
            $this->unLinkStrings($this->managers_orders_columns, [24, 25]);
        }

        $columns = $this->getColumns($this->managers_orders_columns, 'sentToLogist', $this->tableName);
        $names = $this->getColumns($this->managers_orders_column_names, 'sentToLogist', $this->tableName, true);

        if (empty($selected)) {
            $where = [];
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
            $ssp = $this->getSspComplexJson($this->managers_orders_table, "orders.order_id",
                $columns, $input, null, $where);
            $values = json_decode($ssp, true)['data'];
        } else {
            $values = $selected;
        }

        require_once dirname(__FILE__) . '/../classes/Excel.php';
        $excel = new Excel();

        $data = array_merge([$names], $values);
        return $excel->printTable($data, $visible, 'sentToLogist');

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
