<?php

class ModelSent_to_logist extends Model
{
    var $managers_orders_columns = [
        array('dt' => 0, 'db' => "order_items.order_item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/order?id=', orders.order_id, '\">', orders.order_id, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=', orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/product?id=', order_items.product_id, '\" 
                                 target=\"_blank\" data-id=\"', order_items.order_item_id, '\" 
                                 class=\"order-item-product\">', products.name, '</a>')"),
        array('dt' => 4, 'db' => "CONCAT('<span class=\"brand-cell', '\">', brands.name, '</span>')"),
        array('dt' => 5, 'db' => "orders.start_date"),
        array('dt' => 6, 'db' => "order_items.item_status"),
        array('dt' => 7, 'db' => "order_items.amount"),
        array('dt' => 8, 'db' => "products.units"),
        array('dt' => 9, 'db' => "order_items.number_of_packs"),
        array('dt' => 10, 'db' => "products.weight * order_items.number_of_packs"),
        array('dt' => 11, 'db' => "products.purchase_price"),
        array('dt' => 12, 'db' => "products.purchase_price * order_items.number_of_packs"),
        array('dt' => 13, 'db' => "'unknown'"),
        array('dt' => 14, 'db' => "orders.total_price"),
        array('dt' => 15, 'db' => "orders.total_downpayment"),
        array('dt' => 16, 'db' => "orders.downpayment_rate"),
        array('dt' => 17, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 18, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', suppliers_orders_items.order_id, '\">',
            suppliers_orders_items.order_id, '</a>')"),
        array('dt' => 19, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 20, 'db' => "suppliers_orders.release_date"),
        array('dt' => 21, 'db' => "CONCAT('<a href=\"/truck?id=', trucks_items.truck_id, '\">',
            trucks_items.truck_id, '</a>')"),
        array('dt' => 22, 'db' => "trucks.supplier_departure_date"),
        array('dt' => 23, 'db' => "trucks.warehouse_arrival_date"),
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
        'Warehouse Arrival Date'
    ];

    var $supStatuses = [
        0 => 'Sent to Logist',
        1 => 'Sent to Supplier',
        2 => 'Confirmed by Supplier',
        3 => 'Produced',
        4 => 'On the way',
        5 => 'On Stock',
    ];

    public function __construct()
    {
        $this->connect_db();
    }

    var $managers_orders_table = 'orders
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join order_items as order_items on orders.order_id = order_items.order_id
            left join suppliers_orders_items as suppliers_orders_items on 
                (suppliers_orders_items.managers_order_item_id = order_items.order_item_id)
            left join suppliers_orders as suppliers_orders on suppliers_orders.order_id = suppliers_orders_items.order_id
            left join trucks_items as trucks_items on suppliers_orders_items.order_item_id = trucks_items.suppliers_order_item_id
            left join trucks as trucks on (trucks_items.truck_id = trucks.id)
            left join products as products on order_items.product_id = products.product_id
            left join brands as brands on products.brand_id = brands.brand_id';

    var $managers_orders_table_reduce = 'orders
            left join users as managers on orders.sales_manager_id = managers.user_id';

    var $statusesFilter = 'Sent to Logist';

    function getDTManagersOrders($input)
    {
        $where = "(order_items.item_status = '$this->statusesFilter')";

        $this->sspComplex($this->managers_orders_table, "orders.order_id",
            $this->managers_orders_columns, $input, null, $where);
    }

}
