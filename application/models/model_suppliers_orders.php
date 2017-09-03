<?php
include_once 'model_managers_orders.php';

class ModelSuppliers_orders extends ModelManagers_orders
{

    var $tableNames = ["table_suppliers_orders", "table_suppliers_orders_reduced"];
    var $page;

    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "suppliers_orders_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', IFNULL(brands.name, 'no name'), '</a>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', 
            suppliers_orders.order_id,'\">', suppliers_orders.order_id, '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/product?id=', suppliers_orders_items.product_id,  '\"',
            'class=\"order-item-product\" data-id=\"', suppliers_orders_items.supplier_order_id ,'\">', IFNULL(products.visual_name, 'Enter Visual Name!'), '</a>')"),
        array('dt' => 4, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 5, 'db' => "suppliers_orders.release_date"),
        array('dt' => 6, 'db' => "suppliers_orders.departure_date"),
        array('dt' => 7, 'db' => "suppliers_orders_items.warehouse_arrival_date"),
        array('dt' => 8, 'db' => "CONCAT('<a href=\"/order?id=',
                suppliers_orders_items.manager_order_id,
                '\">', suppliers_orders_items.manager_order_id,
                 IF(suppliers_orders_items.reserve_since_date IS NULL, '', (CONCAT(' (reserved ', 
                 suppliers_orders_items.reserve_since_date, ')'))), '</a>')"),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 10, 'db' => "orders.start_date"),
        array('dt' => 11, 'db' => "status.name"),
        array('dt' => 12, 'db' => "CAST(suppliers_orders_items.amount as decimal(64, 3))"),
        array('dt' => 13, 'db' => "CAST(suppliers_orders_items.number_of_packs as decimal(64, 3))"),
        array('dt' => 14, 'db' => "CAST(products.weight * suppliers_orders_items.amount as decimal(64, 3))"),
        array('dt' => 15, 'db' => "CAST(suppliers_orders_items.purchase_price as decimal(64, 2))"),
        array('dt' => 16, 'db' => "CAST(suppliers_orders_items.purchase_price * suppliers_orders_items.amount as decimal(64, 2))"),
        array('dt' => 17, 'db' => "'unknown'"),
        array('dt' => 18, 'db' => "CAST(suppliers_orders.total_price as decimal(64, 2))"),
        array('dt' => 19, 'db' => "suppliers_orders.total_downpayment"),
        array('dt' => 20, 'db' => "CONCAT(orders.downpayment_rate, ' %')"),
        array('dt' => 21, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 22, 'db' => "products.units"),
        array('dt' => 23, 'db' => "CAST(suppliers_orders_items.sell_price as decimal(64, 2))"),
        array('dt' => 24, 'db' => "IFNULL(CAST(suppliers_orders_items.sell_value as decimal(64, 2)), '')"),
        array('dt' => 25, 'db' => "CONCAT('<a href=\"/truck?id=', suppliers_orders_items.truck_id, '\">',
            suppliers_orders_items.truck_id, '</a>')"),
        array('dt' => 26, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.final_name, '</a>')"),
        array('dt' => 27, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', 
            commission.final_name, '</a>')"),
        array('dt' => 28, 'db' => "CAST(suppliers_orders_items.discount_rate as decimal(64, 2))"),
        array('dt' => 29, 'db' => "CAST(suppliers_orders_items.reduced_price as decimal(64, 2))"),
        array('dt' => 30, 'db' => "CAST(suppliers_orders_items.manager_bonus_rate as decimal(64, 2))"),
        array('dt' => 31, 'db' => "CAST(suppliers_orders_items.manager_bonus as decimal(64, 2))"),
        array('dt' => 32, 'db' => "CAST(suppliers_orders_items.commission_rate as decimal(64, 2))"),
        array('dt' => 33, 'db' => "CAST(suppliers_orders_items.commission_agent_bonus as decimal(64, 2))"),
        array('dt' => 34, 'db' => "suppliers_orders_items.production_date"),
        array('dt' => 35, 'db' => "IFNULL(CONCAT(suppliers_orders_items.reserve_since_date, ' - ',
            suppliers_orders_items.reserve_till_date), '')"),
        array('dt' => 36, 'db' => "IFNULL(CAST(CAST(suppliers_orders_items.purchase_price as decimal(64,2)) * 
					products.margin as decimal(64, 2)), '')"),
        array('dt' => 37, 'db' => "IFNULL(CAST(CAST(suppliers_orders_items.purchase_price as decimal(64,2)) * 
					products.margin * suppliers_orders_items.amount as decimal(64, 2)), '')"),
        array('dt' => 38, 'db' => "CAST((CAST(suppliers_orders_items.sell_value as decimal(64, 2)) - 
	                (CAST(CAST(suppliers_orders_items.purchase_price as decimal(64,2)) * 
					products.margin * suppliers_orders_items.amount as decimal(64, 2))) - 
					CAST(suppliers_orders_items.commission_agent_bonus as decimal(64, 2)) - 
					CAST(suppliers_orders_items.manager_bonus as decimal(64, 2))) as decimal(64, 2))"),
        array('dt' => 39, 'db' => "CAST(suppliers_orders_items.sell_price as decimal(64, 2)) - 
                                    CAST(CAST(suppliers_orders_items.purchase_price as decimal(64,2)) * 
					                products.margin as decimal(64, 2))"),

    ];

    var $suppliers_orders_column_names = [
        '_Supplier Order ID',
        'Brand',
        'Supplier Order ID',
        'Product',
        'Date of Order (Supplier)',
        'Supplier Release Date',
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
        'Purchase Value',
        'Sell Price / Unit',
        'Total Sell Price',
        'Downpayment',
        'Downpayment rate',
        'Client\'s expected date of issue',
        'Units',
        'Sell Price / Unit',
        'Total Sell Price',
        'Truck ID',
        'Client',
        'Commission Agent',
        'Discount Rate',
        'Reduced Price',
        'Manager Bonus Rate',
        'Manager Bonus',
        'Commission Rate',
        'Commission Value',
        'Production Date',
        'Reserve Period',
        'Expected Cost',
        'Expected Cost Value',
        'Expected profit',
        'Expected margin/Unit',
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
        '_Supplier Order ID',
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
            left join clients as client on (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join brands as brands on products.brand_id = brands.brand_id';

    var $suppliers_orders_table_reduce = 'suppliers_orders ' .
        'left join order_items on order_items.supplier_order_id = suppliers_orders.order_id '.
        'left join orders on orders.order_id = order_items.manager_order_id '.
        'left join clients client on orders.client_id = client.client_id ' .
        'left join items_status as status on (suppliers_orders.status_id = status.status_id)';

    var $suppliersFilterWhere = ["suppliers_orders_items.supplier_order_id IS NOT NULL",
                                    "suppliers_orders_items.truck_id IS NULL AND suppliers_orders_items.is_deleted = 0"];


    function getSSPData($type = 'general')
    {

        $ssp = ['page' => $this->page];

        switch ($type) {
            case 'general':

                if ($this->user->role_id == ROLE_SALES_MANAGER) {
                    $this->suppliersFilterWhere[] = "(orders.sales_manager_id = " . $this->user->user_id . ' OR' .
                        " client.sales_manager_id = " . $this->user->user_id .
                        " OR client.operational_manager_id = " . $this->user->user_id .
                        ' OR suppliers_orders_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
                    $this->unLinkStrings($this->suppliers_orders_columns, [2, 26, 27]);
                }
                $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_columns, $this->page,
                    $this->tableNames[0]));
                $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_column_names, $this->page,
                    $this->tableNames[0], true));
                $ssp['db_table'] = $this->suppliers_orders_table;
                $ssp['table_name'] = $this->tableNames[0];
                $ssp['primary'] = 'suppliers_orders_items.item_id';
                break;

            case 'reduced':

                $this->suppliersFilterWhere = '';
                if ($this->user->role_id == ROLE_SALES_MANAGER) {
                    $this->suppliersFilterWhere = "orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                        " client.sales_manager_id = " . $this->user->user_id .
                        " OR client.operational_manager_id = " . $this->user->user_id .
                        ' OR order_items.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL';
                    $this->unLinkStrings($this->suppliers_orders_columns_reduce, [1]);
                }
                $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_columns_reduce, $this->page,
                    $this->tableNames[1]));
                $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_column_names_reduce, $this->page,
                    $this->tableNames[1], true));
                $ssp['db_table'] = $this->suppliers_orders_table_reduce;
                $ssp['table_name'] = $this->tableNames[1];
                $ssp['primary'] = 'suppliers_orders.order_id';
                break;

            case 'modal_suppliers_orders':
                $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_columns, $this->page, $type));
                $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_column_names, $this->page,
                    $type, true));
                $ssp['db_table'] = $this->suppliers_orders_table;
                $ssp['table_name'] = $type;
                $ssp['primary'] = 'suppliers_orders_items.item_id';
                break;

        }

        $ssp['where'] = $this->suppliersFilterWhere;
        return $ssp;

    }

    function getDTSuppliersOrders($input, $printOpt, $isReduced = false)
    {

        $type = $isReduced ? 'reduced' : 'general';
        $ssp = $this->getSSPData($type);

        if ($printOpt) {
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $ssp['where']);

    }

    function getSelects($ssp, $isReduced = false)
    {

        $sspJson = $this->getSspComplexJson($ssp['db_table'], $ssp['primary'],
            $ssp['original_columns'], null, null, $ssp['where']);
        $rowValues = json_decode($sspJson, true)['data'];
        $columnsNames = $ssp['original_columns_names'];

        if (!$isReduced) {
            $ignoreArray = ['Supplier Order ID', 'Manager Order ID', 'Quantity', 'Number of Packs', 'Total weight',
                'Purchase Price / Unit', 'Total Purchase Price', 'Sell Price / Unit', 'Total Sell Price', 'Downpayment',
                'Downpayment rate'];
        } else {
            $ignoreArray = [];
        }

        if (!empty($rowValues)) {
            $selects = Helper::getSelectsFromValues($rowValues, $columnsNames, $ignoreArray, true);
            return ['selectSearch' => $selects, 'filterSearchValues' => $rowValues];
        }
        return [];
    }

    public function getTableData($type = 'general')
    {
        $data = $this->getSSPData($type);

        switch ($type) {
            case 'general':
                $cache = new Cache();
                $selects = $cache->getOrSet('suppliers_orders_selects', function() use($data) {
                    return $this->getSelects($data);
                });
                break;
            case 'reduced':
                $selects = $this->getSelects($data, true);
                break;
        }

        return array_merge($data, $selects);
    }

    function addNewOrder($supplier_id) {
        $id = $this->insert("INSERT INTO suppliers_orders (supplier_date_of_order) VALUES (NOW())");
        if ($id && $supplier_id) {
            $this->update("UPDATE suppliers_orders SET supplier_id = $supplier_id");
        }
        return $id;
    }


    function addOrderItem($products, $suppliers_order = 0)
    {
        // Если заказ новый
        if (!$suppliers_order) {
            $suppliers_order = $this->insert("INSERT INTO suppliers_orders (supplier_date_of_order) VALUES (NOW())");
        }
        if ($suppliers_order) {
            $order_items_count = 0;
            $productId = 0;
            foreach ($products as $order_item_id) {
                $order_items_count++;
                $this->update("UPDATE order_items SET supplier_order_id = $suppliers_order,
                              status_id = ".DRAFT_FOR_SUPPLIER." WHERE item_id = $order_item_id");
                $productId = $this->getFirst("SELECT product_id FROM order_items WHERE item_id = $order_item_id");
                $productId = $productId ? $productId['product_id'] : 0;
	            $this->updateItemsStatus($order_item_id);
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

            $this->clearCache(['managers_orders_selects', 'sent_to_logist']);
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

    public function getItems($ids)
    {
        if (!$ids)
            return false;

        $items = $this->getAssoc("SELECT order_items.amount as amount, order_items.item_id, " .
            "products.visual_name as name, order_items.truck_id, order_items.status_id as status_id " .
            "FROM order_items " .
            "LEFT JOIN products ON (order_items.product_id = products.product_id) " .
            "WHERE order_items.item_id IN ($ids)");

        return json_encode($items);
    }

    public function loadIntoTruck($amounts, $truck_id)
    {

        $toTruckIds = [];
        require_once 'model_order.php';
        $orderModel = new ModelOrder();
        foreach ($amounts as $item_id => $amount) {

            $item = $this->getFirst("SELECT item_id, status_id, warehouse_arrival_date, amount FROM order_items 
                  WHERE item_id = $item_id");
            if (floatval($item['amount']) > floatval($amount)) {
                $item_id = $orderModel->split($item_id, $amount);
                if (!$item_id)
                    continue;
            } elseif (floatval($item['amount']) < floatval($amount)) {
                continue;
            }

            $toTruckIds[] = $item_id;
        }

        if (!empty($toTruckIds)) {
            require_once 'model_truck.php';
            $truckModel = new ModelTruck();
            return $truckModel->addTruckItem($toTruckIds, $truck_id);
        }

    }


    function getActiveTrucks()
    {
        $trucks = $this->getAssoc("SELECT id, id as text FROM trucks");
        array_unshift($trucks, ['id' => 0, 'text' => 'New Truck']);
        return $trucks;
    }

}
