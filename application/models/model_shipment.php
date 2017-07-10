<?php

include_once 'model_managers_orders.php';

class ModelShipment extends ModelManagers_orders
{
    var $tableNames = ["table_trucks", "table_trucks_reduced"];

    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "trucks_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/truck?id=', trucks.id,  '\">', trucks.id, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/product?id=', products.product_id,  '\"
            class=\"order-item-product\" data-id=\"', trucks_items.item_id ,'\">', IFNULL(products.visual_name, 'Enter Visual Name!'), '</a>')"),
        array('dt' => 3, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 4, 'db' => "suppliers_orders.release_date"),
        array('dt' => 5, 'db' => "suppliers_orders.departure_date"),
        array('dt' => 6, 'db' => "trucks_items.warehouse_arrival_date"),
        array('dt' => 7, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', suppliers_orders.order_id,  '\">', suppliers_orders.order_id, '</a>')"),
        array('dt' => 8, 'db' => "CONCAT('<a href=\"/order?id=', trucks_items.manager_order_id,  '\">', 
            trucks_items.manager_order_id, IF(trucks_items.reserve_since_date IS NULL, '', ' (reserved)'), '</a>')"),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 10, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', IFNULL(brands.name, 'no name'), '</a>')"),
        array('dt' => 11, 'db' => "orders.start_date"),
        array('dt' => 12, 'db' => "status.name"),
        array('dt' => 13, 'db' => "CAST(trucks_items.amount as decimal(64, 3))"),
        array('dt' => 14, 'db' => "CAST(trucks_items.number_of_packs as decimal(64, 3))"),
        array('dt' => 15, 'db' => "CAST(products.weight * trucks_items.amount as decimal(64, 3))"),
        array('dt' => 16, 'db' => "CAST(trucks_items.purchase_price as decimal(64, 2))"),
        array('dt' => 17, 'db' => "CAST(trucks_items.purchase_price * trucks_items.amount as decimal(64, 2))"),
        array('dt' => 18, 'db' => "'unknown'"),
        array('dt' => 19, 'db' => "CAST(trucks_items.total_price as decimal(64, 2))"),
        array('dt' => 20, 'db' => "CONCAT(orders.downpayment_rate, ' %')"),
        array('dt' => 21, 'db' => "orders.downpayment_rate"),
        array('dt' => 22, 'db' => "orders.expected_date_of_issue"),

        array('dt' => 23, 'db' => "CONCAT('<a href=\"/order?id=',
                trucks_items.manager_order_id,
                '\">', trucks_items.manager_order_id,
                 IF(trucks_items.reserve_since_date IS NULL, '', (CONCAT(' (reserved ', 
                 trucks_items.reserve_since_date, ')'))), '</a>')"),
        array('dt' => 24, 'db' => "products.units"),
        array('dt' => 25, 'db' => "CAST(trucks_items.sell_price as decimal(64, 2))"),
        array('dt' => 26, 'db' => "CAST(trucks_items.sell_price * trucks_items.amount as decimal(64, 2))"),
        array('dt' => 27, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.final_name, '</a>')"),
        array('dt' => 28, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', 
            commission.final_name, '</a>')"),
        array('dt' => 29, 'db' => "trucks_items.discount_rate"),
        array('dt' => 30, 'db' => "trucks_items.reduced_price"),
        array('dt' => 31, 'db' => "trucks_items.manager_bonus_rate"),
        array('dt' => 32, 'db' => "trucks_items.manager_bonus"),
        array('dt' => 33, 'db' => "trucks_items.commission_rate"),
        array('dt' => 34, 'db' => "trucks_items.commission_agent_bonus"),
        array('dt' => 35, 'db' => "trucks_items.production_date"),
        array('dt' => 36, 'db' => "IFNULL(CONCAT(trucks_items.reserve_since_date, ' - ',
            trucks_items.reserve_till_date), '')"),

    ];

    var $suppliers_orders_column_names = [
        '_Truck ID',
        'Truck ID',
        'Product',
        'Date of Order (Supplier)',
        'Supplier Release Date',
        'Supplier Departure Date',
        'Warehouse Arrival Date',
        'Supplier order ID',
        'Manager Order ID',
        'Manager',
        'Brand',
        'Date of Order (Client)',
        'Status',
        'Quantity',
        'Number of Packs',
        'Total weight',
        'Purchase Price / Unit',
        'Total Purchase Price',
        'Sell Price / Unit',
        'Total Sell Price',
        'Downpayment',
        'Downpayment rate',
        'Client\'s expected date of issue',
        'Manager Order ID',
        'Units',
        'Sell Price / Unit',
        'Total Sell Price',
        'Client',
        'Commission Agent',
        'Discount Rate',
        'Reduced Price',
        'Manager Bonus Rate',
        'Manager Bonus',
        'Commission Rate',
        'Commission Agent Bonus',
        'Production Date',
        'Reserve Period',
    ];

    function getSelects()
    {
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $this->filterWhere[] = "orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                " client.sales_manager_id = ". $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id .
                ' OR trucks_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL';
            $this->unLinkStrings($this->suppliers_orders_columns, [1, 7, 27, 28]);
        }

        $columns = $this->getColumns($this->suppliers_orders_columns, 'shipment', $this->suppliers_orders_table);

        $ssp = $this->getSspComplexJson($this->suppliers_orders_table, "trucks_items.item_id", $columns,
            null, null, $this->filterWhere);

        $columnNames = $this->getColumns($this->suppliers_orders_column_names, 'shipment', $this->suppliers_orders_table, true);


        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['Supplier Order ID', 'Truck ID', 'Quantity', 'Number of Packs', 'Total weight',
            'Purchase Price / Unit', 'Total Purchase Price', 'Sell Price / Unit', 'Total Sell Price', 'Downpayment',
            'Downpayment rate', 'Manager Order ID'];

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

    var $suppliers_orders_table = 'order_items as trucks_items
        left join trucks on trucks.id = trucks_items.truck_id
        left join orders on orders.order_id = trucks_items.manager_order_id
        left join suppliers_orders on suppliers_orders.order_id = trucks_items.supplier_order_id
        left join users as managers on orders.sales_manager_id = managers.user_id
        join products on trucks_items.product_id = products.product_id
        left join brands on products.brand_id = brands.brand_id
        left join clients as client on (orders.client_id = client.client_id)
        left join clients as commission on (orders.commission_agent_id = commission.client_id)
        left join items_status as status on trucks_items.status_id = status.status_id
    ';

    var $suppliers_orders_table_reduce = 'trucks 
                                          left join order_items on trucks.id = order_items.truck_id
                                          left join orders on orders.order_id = order_items.manager_order_id
                                          left join clients client on orders.client_id = client.client_id 
                                          left join items_status as status on trucks.status_id = status.status_id';

    var $filterWhere = ["trucks_items.truck_id IS NOT NULL", "trucks_items.warehouse_id IS NULL"];

    function getDTSuppliersOrders($input, $printOpt)
    {
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $this->filterWhere[] = "(orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                " client.sales_manager_id = ". $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id .
                ' OR trucks_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
            $this->unLinkStrings($this->suppliers_orders_columns, [1, 7, 27, 28]);
        }

        $columns = $this->getColumns($this->suppliers_orders_columns, 'shipment', $this->tableNames[0]);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->suppliers_orders_column_names,
            'db_table' => $this->suppliers_orders_table,
            'page' => 'shipment',
            'table_name' => $this->tableNames[0],
            'primary' => 'trucks_items.item_id',
        ];

        if ($printOpt) {

            $printOpt['where'] = $this->filterWhere;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;

        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $this->filterWhere);

    }

    var $suppliers_orders_columns_reduce = [
        array('dt' => 0, 'db' => "trucks.id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/truck?id=', trucks.id,  '\">', trucks.id, '</a>')"),
        array('dt' => 2, 'db' => "trucks.supplier_departure_date"),
        array('dt' => 3, 'db' => "trucks.warehouse_arrival_date"),
        array('dt' => 4, 'db' => "trucks.shipment_price"),
        array('dt' => 5, 'db' => "status.name"),
    ];

    var $suppliers_orders_column_names_reduce = [
        '_Truck ID',
        'Truck ID',
        'Supplier Departure Date',
        'Warehouse Arrival Date',
        'Shipment Price',
        'Status',
    ];

    function getDTSuppliersOrdersReduce($input)
    {
        $where = '';
        if ($_SESSION['user_role'] == ROLE_SALES_MANAGER) {
            $userId = $_SESSION['user_id'];
            $where .= "(orders.sales_manager_id = " . $userId . ' OR '.
                " client.sales_manager_id = $userId ".
                " OR client.operational_manager_id = $userId " .
                ' OR order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';

            $this->unLinkStrings($this->suppliers_orders_columns_reduce, [1]);
        }

        $columns = $this->getColumns($this->suppliers_orders_columns_reduce, 'shipment', $this->tableNames[1]);

        $this->sspComplex($this->suppliers_orders_table_reduce, "trucks.id", $columns,
            $input, null, $where);
    }

}