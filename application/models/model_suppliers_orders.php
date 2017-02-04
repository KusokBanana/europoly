<?php
include_once 'model_managers_orders.php';

class ModelSuppliers_orders extends ModelManagers_orders
{
    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "suppliers_orders_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', IFNULL(brands.name, 'no name'), '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', 
            suppliers_orders.order_id,'\">', suppliers_orders.order_id, '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/product?id=', suppliers_orders_items.product_id,  '\"',
            'class=\"order-item-product\" data-id=\"', suppliers_orders_items.supplier_order_id ,'\">', products.name, '</a>')"),
        array('dt' => 4, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 5, 'db' => "suppliers_orders.release_date"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"/truck?id=', 
            suppliers_orders_items.truck_id,'\">', suppliers_orders_items.truck_id, '</a>')"),
        array('dt' => 7, 'db' => "suppliers_orders.departure_date"),
        array('dt' => 8, 'db' => "suppliers_orders_items.warehouse_arrival_date"),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"/order?id=', 
            suppliers_orders_items.manager_order_id,'\">', suppliers_orders_items.manager_order_id, '</a>')"),
        array('dt' => 10, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=', orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a></a>')"),
        array('dt' => 11, 'db' => "orders.start_date"),
        array('dt' => 12, 'db' => "status.name"),
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
        array('dt' => 3, 'db' => "status.name"),
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

    var $suppliers_orders_table = '
            order_items as suppliers_orders_items
            left join suppliers_orders on suppliers_orders.order_id = suppliers_orders_items.supplier_order_id
            left join orders on (suppliers_orders_items.manager_order_id = orders.order_id) 
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join products as products on suppliers_orders_items.product_id = products.product_id
            left join items_status as status on suppliers_orders_items.status_id = status.status_id
            left join brands as brands on products.brand_id = brands.brand_id';

    var $suppliers_orders_table_reduce = 'suppliers_orders ' .
        'left join items_status as status on (suppliers_orders.status_id = status.status_id)';

    var $suppliersFilterWhere = "suppliers_orders_items.supplier_order_id IS NOT NULL";

    function getDTSuppliersOrders($input)
    {
        $this->sspComplex($this->suppliers_orders_table, "suppliers_orders_items.item_id", $this->suppliers_orders_columns,
            $input, null, $this->suppliersFilterWhere);
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
            $productId = 0;
            foreach ($products as $order_item_id) {
                $order_items_count++;
                $this->update("UPDATE order_items SET supplier_order_id = $suppliers_order,
                              status_id = 4 WHERE item_id = $order_item_id");
                $productId = $this->getFirst("SELECT product_id FROM order_items WHERE item_id = $order_item_id");
                $productId = $productId ? $productId['product_id'] : 0;
            }
            $supplier = $this->getFirst("SELECT supplier_id FROM suppliers_orders WHERE order_id = $suppliers_order");
            $supplier_id = ($supplier && $supplier['supplier_id'] !== null) ? $supplier['supplier_id'] : null;
            if ($productId) {
                $brand = $this->getFirst("SELECT brand_id FROM products WHERE product_id = $productId");
                $brand = $brand ? $brand['brand_id'] : 0;
                if ($brand) {
                    $supplier_id = $this->getFirst("SELECT supplier_id FROM brands WHERE brand_id = $brand");
                    $supplier_id = $supplier_id ? $supplier_id['supplier_id'] : null;
                }
            }
            // Обновим количество товаров
            $order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $suppliers_order");
            $order_items_count += intval($order['order_items_count']);
            $this->update("UPDATE suppliers_orders SET supplier_id = $supplier_id,
                              order_items_count = $order_items_count WHERE order_id = $suppliers_order");

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
                $orderItem = $this->getFirst("SELECT * FROM order_items WHERE supplier_order_id = ${order['order_id']}");
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
            $where .= "(suppliers_orders_items.item_id=". $product .")";
            $where .= ($count-$key > 1) ? ' OR ' : '';
        }
        $where2 = 'suppliers_orders_items.supplier_order_id IS NOT NULL AND suppliers_orders_items.truck_id IS NULL';

        $this->sspComplex($this->suppliers_orders_table, "suppliers_orders_items.item_id",
            $this->suppliers_orders_columns, $input, $where2, $where);
    }


    public function updateItemsStatus($orderId)
    {
        $status = $this->getFirst("SELECT status_id FROM order_items WHERE supplier_order_id = $orderId AND 
                                    status_id = (SELECT MIN(status_id) FROM order_items)");
        $orderStatus = $status ? $status['status_id'] : 4;
        $this->update("UPDATE suppliers_orders 
                SET status_id = $orderStatus WHERE order_id = $orderId");
    }

}
