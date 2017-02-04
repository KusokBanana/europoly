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

}
