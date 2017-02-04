<?php

class ModelManagers_orders extends Model
{
    var $managers_orders_columns = [
        array('dt' => 0, 'db' => "order_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/order?id=', order_items.manager_order_id, '\">', order_items.manager_order_id, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=',
            orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/product?id=', order_items.product_id, '\"',
                                 'target=\"_blank\" data-id=\"', order_items.item_id, '\"',
                                 'class=\"order-item-product\">', products.name, '</a>')"),
        array('dt' => 4, 'db' => "CONCAT('<span class=\"brand-cell', '\">', brands.name, '</span>')"),
        array('dt' => 5, 'db' => "orders.start_date"),
        array('dt' => 6, 'db' => "status.name"),
        array('dt' => 7, 'db' => "order_items.amount"),
        array('dt' => 8, 'db' => "products.units"),
        array('dt' => 9, 'db' => "order_items.number_of_packs"),
        array('dt' => 10, 'db' => "products.weight * order_items.number_of_packs"),
        array('dt' => 11, 'db' => "order_items.purchase_price"),
        array('dt' => 12, 'db' => "order_items.purchase_price * order_items.number_of_packs"),
        array('dt' => 13, 'db' => "order_items.sell_price"),
        array('dt' => 14, 'db' => "order_items.sell_price * order_items.number_of_packs"),
        array('dt' => 15, 'db' => "orders.total_downpayment"),
        array('dt' => 16, 'db' => "orders.downpayment_rate"),
        array('dt' => 17, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 18, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', order_items.supplier_order_id, '\">',
            order_items.supplier_order_id, '</a>')"),
        array('dt' => 19, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 20, 'db' => "suppliers_orders.release_date"),
        array('dt' => 21, 'db' => "CONCAT('<a href=\"/truck?id=', order_items.truck_id, '\">',
            order_items.truck_id, '</a>')"),
        array('dt' => 22, 'db' => "trucks.supplier_departure_date"),
        array('dt' => 23, 'db' => "order_items.warehouse_arrival_date"),
        array('dt' => 24, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.name, '</a>')"),
        array('dt' => 25, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.name, '</a>')"),
    ];

    var $managers_orders_column_names = [
        'Manager Order ID',
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
         'Commission Agent'
    ];

    var $managers_orders_reduced_columns = [
        array('dt' => 0, 'db' => "orders.order_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/order?id=', orders.order_id, '\">', orders.order_id, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=', orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 3, 'db' => "orders.start_date"),
        array('dt' => 4, 'db' => "status.name"),
        array('dt' => 5, 'db' => "orders.total_price"),
        array('dt' => 6, 'db' => "orders.total_downpayment"),
        array('dt' => 7, 'db' => "orders.downpayment_rate"),
        array('dt' => 8, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.name, '</a>')"),
        array('dt' => 10, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.name, '</a>')"),
    ];

    var $managers_orders_reduced_column_names = [
        'Order ID',
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
            left join clients as client on (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join brands on products.brand_id = brands.brand_id';

    var $managers_orders_table_reduce = 'orders
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join clients as client on (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join items_status as status on orders.order_status_id = status.status_id';

    function getDTManagersOrders($input)
    {
        $this->sspComplex($this->managers_orders_table, "order_items.item_id",
            $this->managers_orders_columns, $input, null, null);
    }

    function getDTManagersOrdersReduced($input)
    {
        $this->sspComplex($this->managers_orders_table_reduce, "orders.order_id",
            $this->managers_orders_reduced_columns, $input, null, null);
    }

    function getDTManagersOrdersToSuppliersOrder($input, $products)
    {
        $where = '';
        $count = count($products);

        foreach ($products as $key => $product) {
            $where .= "(order_items.item_id=". $product .")";
            $where .= ($count-$key > 1) ? ' OR ' : '';
        }

        $this->sspComplex($this->managers_orders_table, "DISTINCT order_items.item_id",
            $this->managers_orders_columns, $input, null, $where);
    }

}
