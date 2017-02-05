<?php

include_once 'model_managers_orders.php';

class ModelShipment extends ModelManagers_orders
{

    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "trucks_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/truck?id=', trucks.id,  '\">', trucks.id, '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/product?id=', products.product_id,  '\"
            class=\"order-item-product\" data-id=\"', trucks_items.item_id ,'\">', products.name, '</a>')"),
        array('dt' => 3, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 4, 'db' => "suppliers_orders.release_date"),
        array('dt' => 5, 'db' => "suppliers_orders.departure_date"),
        array('dt' => 6, 'db' => "trucks_items.warehouse_arrival_date"),
        array('dt' => 7, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', suppliers_orders.order_id,  '\">', suppliers_orders.order_id, '</a>')"),
        array('dt' => 8, 'db' => "CONCAT('<a href=\"/order?id=', trucks_items.manager_order_id,  '\">', 
            trucks_items.manager_order_id, IF(trucks_items.reserve_since_date IS NULL, '', ' (reserved)'), '</a>')"),
        array('dt' => 9, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=', orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 10, 'db' => "brands.name"),
        array('dt' => 11, 'db' => "orders.start_date"),
        array('dt' => 12, 'db' => "status.name"),
        array('dt' => 13, 'db' => "trucks_items.amount"),
        array('dt' => 14, 'db' => "trucks_items.number_of_packs"),
        array('dt' => 15, 'db' => "products.weight * trucks_items.number_of_packs"),
        array('dt' => 16, 'db' => "trucks_items.purchase_price"),
        array('dt' => 17, 'db' => "trucks_items.purchase_price * trucks_items.number_of_packs"),
        array('dt' => 18, 'db' => "'unknown'"),
        array('dt' => 19, 'db' => "trucks_items.total_price"),
        array('dt' => 20, 'db' => "orders.total_downpayment"),
        array('dt' => 21, 'db' => "orders.downpayment_rate"),
        array('dt' => 22, 'db' => "orders.expected_date_of_issue"),

    ];

    var $suppliers_orders_column_names = [
        'Truck ID',
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
    ];

    var $suppliers_orders_table = 'order_items as trucks_items
        left join trucks on trucks.id = trucks_items.truck_id
        left join orders on orders.order_id = trucks_items.manager_order_id
        left join suppliers_orders on suppliers_orders.order_id = trucks_items.supplier_order_id
        left join users as managers on orders.sales_manager_id = managers.user_id
        join products on trucks_items.product_id = products.product_id
        left join brands on products.brand_id = brands.brand_id
        left join items_status as status on trucks_items.status_id = status.status_id
    ';

    var $suppliers_orders_table_reduce = 'trucks 
                                          left join items_status as status on trucks.status_id = status.status_id';

    function getDTSuppliersOrders($input)
    {
        $where = "trucks_items.truck_id IS NOT NULL AND trucks_items.warehouse_id IS NULL";
        $this->sspComplex($this->suppliers_orders_table, "trucks_items.item_id", $this->suppliers_orders_columns,
            $input, null, $where);
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
        'Truck ID',
        'Truck ID',
        'Supplier Departure Date',
        'Warehouse Arrival Date',
        'Shipment Price',
        'Status',
    ];

    function getDTSuppliersOrdersReduce($input)
    {
        $this->sspComplex($this->suppliers_orders_table_reduce, "trucks.id", $this->suppliers_orders_columns_reduce,
            $input, null, null);
    }

}