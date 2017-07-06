<?php

class ModelManagers_orders extends Model
{

    var $tableNames = ["table_managers_orders", "table_managers_orders_reduced"];

    var $managers_orders_columns = [
        array('dt' => 0, 'db' => "order_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/order?id=', order_items.manager_order_id, '\">', 
            IFNULL(orders.visible_order_id, orders.order_id),
            IF(order_items.reserve_since_date IS NULL, '', ' (reserved)'), '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/product?id=', order_items.product_id, '\"',
                                 'target=\"_blank\" data-id=\"', order_items.item_id, '\"',
                                 'class=\"order-item-product\">', IFNULL(products.visual_name, 'Enter Visual Name!'), '</a>')"),
        array('dt' => 4, 'db' => "CONCAT('<span class=\"brand-cell', '\">', brands.name, '</span>')"),
        array('dt' => 5, 'db' => "orders.start_date"),
        array('dt' => 6, 'db' => "status.name"),
        array('dt' => 7, 'db' => "order_items.amount"),
        array('dt' => 8, 'db' => "products.units"),
        array('dt' => 9, 'db' => "order_items.number_of_packs"),
        array('dt' => 10, 'db' => "CAST(products.weight * order_items.amount as decimal(64, 3))"),
        array('dt' => 11, 'db' => "CAST(order_items.purchase_price as decimal(64, 2))"),
        array('dt' => 12, 'db' => "CAST(order_items.purchase_price * order_items.amount as decimal(64, 2))"),
        array('dt' => 13, 'db' => "CAST(order_items.sell_price as decimal(64, 2))"),
        array('dt' => 14, 'db' => "CAST(order_items.sell_price * order_items.amount as decimal(64, 2))"),
        array('dt' => 15, 'db' => "CAST(orders.total_downpayment as decimal(64, 2))"),
        array('dt' => 16, 'db' => "CAST(orders.downpayment_rate as decimal(64, 2))"),
        array('dt' => 17, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 18, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', order_items.supplier_order_id, '\">',
            order_items.supplier_order_id, '</a>')"),
        array('dt' => 19, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 20, 'db' => "suppliers_orders.release_date"),
        array('dt' => 21, 'db' => "CONCAT('<a href=\"/truck?id=', order_items.truck_id, '\">',
            order_items.truck_id, '</a>')"),
        array('dt' => 22, 'db' => "trucks.supplier_departure_date"),
        array('dt' => 23, 'db' => "order_items.warehouse_arrival_date"),
        array('dt' => 24, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.final_name, '</a>')"),
        array('dt' => 25, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.final_name, '</a>')"),
        array('dt' => 26, 'db' => "CAST(order_items.discount_rate as decimal(64, 2))"),
        array('dt' => 27, 'db' => "CAST(order_items.reduced_price as decimal(64, 2))"),
        array('dt' => 28, 'db' => "CAST(order_items.manager_bonus_rate as decimal(64, 2))"),
        array('dt' => 29, 'db' => "order_items.manager_bonus"),
        array('dt' => 30, 'db' => "CAST(order_items.commission_rate as decimal(64, 2))"),
        array('dt' => 31, 'db' => "CAST(order_items.commission_agent_bonus as decimal(64, 2))"),
        array('dt' => 32, 'db' => "order_items.production_date"),
        array('dt' => 33, 'db' => "IFNULL(CONCAT(order_items.reserve_since_date, ' - ',order_items.reserve_till_date), '')"),
        array('dt' => 34, 'db' => "orders.comment"),
    ];

    var $managers_orders_column_names = [
        '_Manager Order ID',
        'Manager Order ID',
        'Manager',
        'Product',
        'Brand',
        'Date of Order (Client)',
        'Status',
        'Quantity',
        'Units',
        'Number of Packs',
        'Total Weight',
        'Purchase Price / Unit',
        'Total Purchase Price',
        'Sell Price / Unit',
        'Total Sell Price',
        'Downpayment',
        'Downpayment rate',
        'Client\'s expected date of issue',
        'Supplier Order ID',
        'Date of Order (Supplier)',
         'Supplier Release Date',
         'Truck ID',
         'Supplier Departure Date',
         'Warehouse Arrival Date',
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
         'Comments',
    ];

    var $managers_orders_reduced_columns = [
        array('dt' => 0, 'db' => "orders.order_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/order?id=', orders.order_id, '\">', 
            IFNULL(orders.visible_order_id, orders.order_id), '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 3, 'db' => "orders.start_date"),
        array('dt' => 4, 'db' => "status.name"),
        array('dt' => 5, 'db' => "orders.total_price"),
        array('dt' => 6, 'db' => "orders.total_downpayment"),
        array('dt' => 7, 'db' => "orders.downpayment_rate"),
        array('dt' => 8, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.final_name, '</a>')"),
        array('dt' => 10, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.final_name, '</a>')"),
    ];

    var $managers_orders_reduced_column_names = [
        '_Order ID',
        'Order ID',
        'Manager',
        'Date of Order',
        'Status',
        'Total Sell Price',
        'Downpayment',
        'Downpayment rate',
        'Client\'s expected date of issue',
        'Commission Agent',
        'Client'
    ];

    public function __construct()
    {
        $this->connect_db();
    }

    var $managers_orders_table = 'order_items
            left join orders on orders.order_id = order_items.manager_order_id
            left join suppliers_orders on order_items.supplier_order_id = suppliers_orders.order_id
            left join trucks on order_items.truck_id = trucks.id
            left join items_status as status on order_items.status_id = status.status_id
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join products on order_items.product_id = products.product_id
            left join clients as client ON (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join brands on products.brand_id = brands.brand_id';

    var $managers_orders_table_reduce = 'orders
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join clients as client on (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join order_items on (order_items.manager_order_id = orders.order_id)
            left join items_status as status on orders.order_status_id = status.status_id';

    var $whereCondition = "order_items.manager_order_id IS NOT NULL AND order_items.is_deleted = 0";

    function getDTManagersOrders($input, $printOpt)
    {

        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $this->whereCondition .= " AND orders.sales_manager_id = " . $this->user->user_id;
            $this->whereCondition = '(' . $this->whereCondition . ')' . " OR client.sales_manager_id = " .
                $this->user->user_id . " OR client.operational_manager_id = " . $this->user->user_id;
            $this->unLinkStrings($this->managers_orders_columns, [24, 25]);
        }

        $columns = $this->getColumns($this->managers_orders_columns, 'managersOrders', $this->tableNames[0]);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->managers_orders_column_names,
            'db_table' => $this->managers_orders_table,
            'page' => 'managersOrders',
            'table_name' => $this->tableNames[0],
            'primary' => 'order_items.item_id',
        ];

        if ($printOpt) {

            $printOpt['where'] = $this->whereCondition;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;

        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $this->whereCondition);
    }

    function getDTManagersOrdersReduced($input)
    {
        $where = '';
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $where = "orders.sales_manager_id = " . $this->user->user_id;
            $where .= " OR client.sales_manager_id = " . $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id;
            $this->unLinkStrings($this->managers_orders_reduced_columns, [9, 10]);
        }

        $columns = $this->getColumns($this->managers_orders_reduced_columns, 'managersOrders', $this->tableNames[1]);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->managers_orders_reduced_column_names,
            'db_table' => $this->managers_orders_table_reduce,
            'page' => 'managersOrders',
            'table_name' => $this->tableNames[1],
            'primary' => 'orders.order_id',
        ];

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $where);
    }

    function getDTManagersOrdersToSuppliersOrder($input, $products)
    {
        $where = '(order_items.is_deleted = 0) AND (';
        $count = count($products);

        foreach ($products as $key => $product) {
            $where .= "(order_items.item_id=". $product .")";
            $where .= ($count-$key > 1) ? ' OR ' : '';
        }
        $where .= ')';

        $this->sspComplex($this->managers_orders_table, "order_items.item_id",
            $this->managers_orders_columns, $input, null, $where);
    }

    function getSelects($isReduced = false)
    {
        if (!$isReduced) {
            $tableNames = $this->getColumns($this->managers_orders_columns,
                'managersOrders', $this->tableNames[0], true);

            if ($this->user->role_id == ROLE_SALES_MANAGER) {
                $where = " orders.sales_manager_id = " . $this->user->user_id;
                $this->whereCondition = '((' . $where . ')' . " OR client.sales_manager_id = " . $this->user->user_id .
                    " OR client.operational_manager_id = " . $this->user->user_id . ') AND order_items.is_deleted = 0';
                $this->unLinkStrings($tableNames, [9, 10]);
            }

            $ssp = $this->getSspComplexJson($this->managers_orders_table, "order_items.item_id",
                $tableNames, null, null, $this->whereCondition);
            $columns = $this->getColumns($this->managers_orders_column_names, 'managersOrders', $this->tableNames[0]);
            $rowValues = json_decode($ssp, true)['data'];
            $ignoreArray = ['Manager Order ID', 'Quantity', 'Number of Packs', 'Total Weight', 'Purchase Price / Unit',
                'Total Purchase Price', 'Sell Price / Unit', 'Total Sell Price', 'Downpayment', 'Downpayment rate',
                'Supplier Order ID', 'Truck ID'];
        } else {

            $tableNames = $this->getColumns($this->managers_orders_reduced_columns,
                'managersOrders', $this->tableNames[1], true);

            if ($this->user->role_id == ROLE_SALES_MANAGER) {
                $this->whereCondition = "orders.sales_manager_id = " . $this->user->user_id;
                $this->whereCondition .= " OR client.sales_manager_id = " . $this->user->user_id .
                    " OR client.operational_manager_id = " . $this->user->user_id;
                $this->unLinkStrings($tableNames, [9, 10]);
            }

            $ssp = $this->getSspComplexJson($this->managers_orders_table_reduce, "orders.order_id",
                $tableNames, null, null, $this->whereCondition);
            $columns = $this->getColumns($this->managers_orders_reduced_column_names, 'managersOrders', $this->tableNames[1]);
            $rowValues = json_decode($ssp, true)['data'];
            $ignoreArray = [];

        }


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
