<?php
include_once 'model_managers_orders.php';

class ModelSent_to_logist extends ModelManagers_orders
{

    var $statusesFilter = 3;

    function getDTManagersOrders($input)
    {
        $where = "(order_items.status_id = '$this->statusesFilter')";

        $this->sspComplex($this->managers_orders_table, "orders.order_id",
            $this->managers_orders_columns, $input, null, $where);
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
