<?php

class ModelSuppliers_orders extends Model
{
    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "suppliers_orders_items.order_item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', IFNULL(brands.name, 'no name'), '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', 
            suppliers_orders.order_id,'\">', suppliers_orders.order_id, '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/product?id=', suppliers_orders_items.product_id,  '\" 
            class=\"order-item-product\" data-id=\"', suppliers_orders_items.order_item_id ,'\">', products.name, '</a>')"),
        array('dt' => 4, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 5, 'db' => "suppliers_orders.release_date"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"/truck?id=', 
            trucks_items.truck_id,'\">', trucks_items.truck_id, '</a>')"),
        array('dt' => 7, 'db' => "suppliers_orders.departure_date"),
        array('dt' => 8, 'db' => "trucks_items.warehouse_arrival_date"),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"/order?id=', 
            order_items.order_id,'\">', order_items.order_id, '</a>')"),
        array('dt' => 10, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=', orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 11, 'db' => "orders.start_date"),
        array('dt' => 12, 'db' => "IFNULL(order_items.item_status, suppliers_orders_items.item_status)"),
        array('dt' => 13, 'db' => "suppliers_orders_items.amount"),
        array('dt' => 14, 'db' => "suppliers_orders_items.number_of_packs"),
        array('dt' => 15, 'db' => "products.weight * suppliers_orders_items.number_of_packs"),
        array('dt' => 16, 'db' => "products.purchase_price"),
        array('dt' => 17, 'db' => "products.purchase_price * suppliers_orders_items.number_of_packs"),
        array('dt' => 18, 'db' => "'unknown'"),
        array('dt' => 19, 'db' => "suppliers_orders.total_price"),
        array('dt' => 20, 'db' => "suppliers_orders.total_downpayment"),
        array('dt' => 21, 'db' => "orders.downpayment_rate"),
        array('dt' => 22, 'db' => "orders.expected_date_of_issue"),

    ];

    var $suppliers_orders_column_names = [
        'Supplier Order ID',
        'Brand',
        'Supplier Order ID',
        'Product',
        'Date of Order (Supplier)',
        'Supplier Release Date',
        'Truck ID',
        'Supplier Departure Date',
        'Warehouse Arrival Date',
        'Manager Order ID',
        'Manager',
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

    var $suppliers_orders_columns_reduce = [
        array('dt' => 0, 'db' => "suppliers_orders.order_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', 
            suppliers_orders.order_id,'\">', suppliers_orders.order_id, '</a>')"),
        array('dt' => 2, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 3, 'db' => "suppliers_orders.status"),
        array('dt' => 4, 'db' => "suppliers_orders.release_date"),
        array('dt' => 5, 'db' => "suppliers_orders.total_price"),
    ];

    var $suppliers_orders_column_names_reduce = [
        'Supplier Order ID',
        'Supplier Order ID',
        'Date of Order (Supplier)',
        'Status',
        'Release Date',
        'Total Purchase Price',
    ];

    public function __construct()
    {
        $this->connect_db();
    }

    var $suppliers_orders_table = 'suppliers_orders
            left join suppliers_orders_items as suppliers_orders_items on suppliers_orders.order_id = suppliers_orders_items.order_id
            left join order_items as order_items on (suppliers_orders_items.managers_order_item_id = order_items.order_item_id)
            left join orders as orders on (order_items.order_id = orders.order_id) 
            left join trucks_items as trucks_items on trucks_items.suppliers_order_item_id = suppliers_orders_items.order_item_id
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join products as products on suppliers_orders_items.product_id = products.product_id
            left join brands as brands on products.brand_id = brands.brand_id';

    var $suppliers_orders_table_reduce = 'suppliers_orders';

    var $statusesFilter = [
        'Draft for Supplier',
        'Confirmed by Supplier',
        'Produced'
    ];

    function getDTSuppliersOrders($input)
    {
//        $statuses = join("', '", $this->statusesFilter);
//        $where = "suppliers_orders_items.item_status IN ('$statuses') OR
//                    order_items.item_status IN ('$statuses')";
        $this->sspComplex($this->suppliers_orders_table, "suppliers_orders.order_id", $this->suppliers_orders_columns,
            $input, null, null);
    }

    function getDTSuppliersOrdersReduce($input)
    {
        $this->sspComplex($this->suppliers_orders_table_reduce, "suppliers_orders.order_id", $this->suppliers_orders_columns_reduce,
            $input, null, null);
    }

    function addOrderItem($products, $suppliers_order = 0)
    {
        // Если заказ новый
        if (!$suppliers_order) {
            $this->insert("INSERT INTO suppliers_orders (supplier_date_of_order) VALUES (NOW())");
            $suppliers_order = $this->insert_id;
        }
        if ($suppliers_order) {
            $order_items_count = 0;
            foreach ($products as $order_item_id) {
                $order_item = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $order_item_id");

                $this->update("UPDATE order_items 
                              SET item_status = 'Draft for Supplier' WHERE order_item_id = $order_item_id");

                $productId = $order_item['product_id'];
                $amount = $order_item['amount'];
                $order_id = $order_item['order_id'];

                $product = $this->getFirst("SELECT * FROM products WHERE product_id = $productId");

                $number_of_packs = $product['amount_in_pack'] != null ? 0 : 1;

                if ($product['amount_in_pack'] != null) {
                    $number_of_packs = ceil($order_item['amount'] / $product['amount_in_pack']);
                    $amount = $number_of_packs * $product['amount_in_pack'];
                } else {
                    $number_of_packs = 1;
                    $amount = $order_item['amount'];
                }
                $amount = $amount ? $amount : 1;

                $total_price = $order_item['purchase_price'] * $amount;

                $this->insert("INSERT INTO suppliers_orders_items (order_id, product_id, amount,
                                number_of_packs, total_price, item_status, managers_order_item_id)
                                VALUES ($suppliers_order, $productId, $amount, 
                                $number_of_packs, $total_price, null, $order_item_id)");

                $order_items_count += $amount;
            }
            // Обновим количество товаров
            $order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $suppliers_order");
            $order_items_count += intval($order['order_items_count']);
            $this->update("UPDATE suppliers_orders 
                              SET order_items_count = $order_items_count WHERE order_id = $suppliers_order");

            $this->updateItemsStatus($suppliers_order);
        }
        return $suppliers_order;
    }

    function getActiveSuppliersOrders()
    {
        $orders = $this->getAssoc("SELECT * FROM suppliers_orders");
        $orderIds = [];
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $orderItem = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_id = ${order['order_id']}");
                $brandName = '';
                if ($orderItem && !empty($orderItem)) {
                    $productId = $orderItem['product_id'];
                    $product = $this->getFirst("SELECT * FROM products WHERE product_id = $productId");
                    $brand = $this->getFirst("SELECT * FROM brands WHERE brand_id = ${product['brand_id']}");
                    $brandName = $brand['name'];
                }

                $orderIds[] = [
                    'id' => $order['order_id'],
                    'date' => $order['supplier_date_of_order'],
                    'brand' => $brandName
                ];
            }
        }
        return $orderIds;
    }

    function getDTSuppliersOrdersToTruck($input, $products)
    {
        $where = '';
        $count = count($products);

        foreach ($products as $key => $product) {
            $where .= "(suppliers_orders_items.order_item_id=". $product .")";
            $where .= ($count-$key > 1) ? ' OR ' : '';
        }

        $this->sspComplex($this->suppliers_orders_table, "suppliers_orders.order_id",
            $this->suppliers_orders_columns, $input, $where, null);
    }


    public function updateItemsStatus($orderId)
    {
        $orderItems = $this->getAssoc("SELECT * FROM suppliers_orders_items WHERE order_id = $orderId");
        $status=10;

        if (!empty($orderItems))
            foreach ($orderItems as $orderItem) {
                if ($orderItem['item_status'] && !$orderItem['managers_order_item_id']) {
                    $itemStatus = $orderItem['item_status'];
                }
                else {
                    $managerOrderItem = $this->getFirst("SELECT * FROM order_items 
                        WHERE order_item_id = ${orderItem['managers_order_item_id']}");
                    $itemStatus = $managerOrderItem['item_status'];
                }
                $newStatus = array_search($itemStatus, $this->statuses);

                if ($newStatus < $status)
                    $status = $newStatus;
            }
        $orderStatus = isset($this->statuses[$status]) ? $this->statuses[$status] : '';
        $this->update("UPDATE suppliers_orders 
                SET status = '$orderStatus' WHERE order_id = $orderId");
    }

}
