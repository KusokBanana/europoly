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
            $where .= " AND (orders.sales_manager_id = " . $_SESSION['user_id'] . ' OR 
                order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
        }

        $columns = $this->getColumns($this->managers_orders_columns, 'sentToLogist', $this->tableName);

        $this->sspComplex($this->managers_orders_table, "orders.order_id",
            $columns, $input, null, $where);
    }

    function getSelects()
    {
        $where = "(order_items.status_id = '$this->statusesFilter')";

        $ssp = $this->getSspComplexJson($this->managers_orders_table, "orders.order_id",
            $this->managers_orders_columns, null, null, $where);
        $columns = $this->managers_orders_column_names;
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
                    $name = $columns[$key];
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
