<?php

include_once 'model_managers_orders.php';

class ModelWarehouse extends ModelManagers_orders
{
    var $tableName = 'table_accountant';

    var $products_warehouses_table = "order_items as products_warehouses
            left join products on products_warehouses.product_id = products.product_id
            left join brands on products.brand_id = brands.brand_id
            left join orders on products_warehouses.manager_order_id = orders.order_id
            left join suppliers_orders on products_warehouses.supplier_order_id = suppliers_orders.order_id
            left join trucks on products_warehouses.truck_id = trucks.id
            left join clients as client on (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join items_status as status on products_warehouses.status_id = status.status_id
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join warehouses on products_warehouses.warehouse_id = warehouses.warehouse_id";

    var $product_warehouses_columns = array(
        array('dt' => 0, 'db' => 'products_warehouses.item_id'),
        array('dt' => 1, 'db' => 'products.article'),
        array('dt' => 2, 'db' => 'CONCAT(\'<a href="/product?id=\', products.product_id, \'">\', 
            IFNULL(products.visual_name, \'Enter Visual Name!\'), \'</a>\')'),
        array('dt' => 3, 'db' => 'CONCAT(\'<a href="/brand?id=\', brands.brand_id, \'">\', IFNULL(brands.name, \'no name\'), \'</a>\')'),
        array('dt' => 4, 'db' => 'CONCAT(\'<a href="javascript:;" class="x-editable x-warehouse_id" 
            data-pk="\', products_warehouses.item_id ,\'" 
            data-name="warehouse_id" data-value="\', products_warehouses.warehouse_id, \'" data-url="/warehouse/change_item_field" 
            data-original-title="Change Warehouse">\', IFNULL(warehouses.name, \'no name\'), \'</a>
            <a href="/warehouse?id=\', products_warehouses.warehouse_id, \'"><i class="glyphicon glyphicon-link"></i></a>\')'),
        array('dt' => 5, 'db' => "IF(ISNULL(products_warehouses.manager_order_id), 
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-amount\" data-pk=\"',
                products_warehouses.item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(products_warehouses.amount, ''),
                '\" data-url=\"/warehouse/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(products_warehouses.amount), ''),
                '</a>'),
                products_warehouses.amount)"),
        array('dt' => 6, 'db' => 'products.units'),
        array('dt' => 7, 'db' => "IF(ISNULL(products_warehouses.manager_order_id), 
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                products_warehouses.item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(products_warehouses.number_of_packs, ''),
                '\" data-url=\"/warehouse/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(products_warehouses.number_of_packs, ''),
                '</a>'), products_warehouses.number_of_packs)"),
        array('dt' => 8, 'db' => "products.weight * products_warehouses.number_of_packs"),
        array('dt' => 9, 'db' => "IF(ISNULL(products_warehouses.manager_order_id), 
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-purchase_price\" data-pk=\"',
                products_warehouses.item_id,
                '\" data-name=\"purchase_price\" data-value=\"',
                IFNULL(products_warehouses.purchase_price, ''),
                '\" data-url=\"/warehouse/change_item_field\" data-original-title=\"Enter Purchase Price\">',
                    IFNULL(CAST(products_warehouses.purchase_price as decimal(64, 2)), ''),
                '</a>'), CAST(products_warehouses.purchase_price as decimal(64, 2)))"),
        array('dt' => 10, 'db' => 'products_warehouses.buy_and_taxes'),
        array('dt' => 11, 'db' => 'CAST(products_warehouses.sell_price as decimal(64, 2))'),
        array('dt' => 12, 'db' => 'CAST(products_warehouses.dealer_price as decimal(64, 2))'),
        array('dt' => 13, 'db' => 'CAST(products_warehouses.total_price as decimal(64, 2))'),
        array('dt' => 14, 'db' => 'CAST((products_warehouses.purchase_price * products_warehouses.amount + products_warehouses.import_vat + products_warehouses.import_brokers_price + products_warehouses.import_tax + products_warehouses.delivery_price) as decimal(64, 2))'),
        array('dt' => 15, 'db' => 'CAST((products_warehouses.purchase_price * products_warehouses.amount + 
        products_warehouses.import_vat + products_warehouses.import_brokers_price + products_warehouses.import_tax +
         products_warehouses.delivery_price) / products_warehouses.amount as decimal(64, 2))'),
        array('dt' => 16, 'db' => "CONCAT('<a href=\"/order?id=', products_warehouses.manager_order_id, '\">', 
            products_warehouses.manager_order_id,
            IF(products_warehouses.reserve_since_date IS NULL, '', ' (reserved)'), '</a>')"),
        array('dt' => 17, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=',
            orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a>')"),
        array('dt' => 18, 'db' => "orders.start_date"),
        array('dt' => 19, 'db' => "status.name"),
        array('dt' => 20, 'db' => "CAST(products_warehouses.purchase_price as decimal(64, 2))"),
        array('dt' => 21, 'db' => "CAST((products_warehouses.purchase_price * products_warehouses.number_of_packs) as decimal(64, 2))"),
        array('dt' => 22, 'db' => "CAST(products_warehouses.sell_price as decimal(64, 2))"),
        array('dt' => 23, 'db' => "CAST((products_warehouses.sell_price * products_warehouses.number_of_packs) as decimal(64, 2))"),
        array('dt' => 24, 'db' => "CAST(orders.total_downpayment as decimal(64, 2))"),
        array('dt' => 25, 'db' => "CAST(orders.downpayment_rate as decimal(64, 2))"),
        array('dt' => 26, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 27, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', products_warehouses.supplier_order_id, '\">',
            products_warehouses.supplier_order_id, '</a>')"),
        array('dt' => 28, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 29, 'db' => "suppliers_orders.release_date"),
        array('dt' => 30, 'db' => "CONCAT('<a href=\"/truck?id=', products_warehouses.truck_id, '\">',
            products_warehouses.truck_id, '</a>')"),
        array('dt' => 31, 'db' => "trucks.supplier_departure_date"),
        array('dt' => 32, 'db' => "products_warehouses.warehouse_arrival_date"),
        array('dt' => 33, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.final_name, '</a>')"),
        array('dt' => 34, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.final_name, '</a>')"),
        array('dt' => 35, 'db' => "CAST(products_warehouses.discount_rate as decimal(64, 2))"),
        array('dt' => 36, 'db' => "CAST(products_warehouses.reduced_price as decimal(64, 2))"),
        array('dt' => 37, 'db' => "CAST(products_warehouses.manager_bonus_rate as decimal(64, 2))"),
        array('dt' => 38, 'db' => "CAST(products_warehouses.manager_bonus as decimal(64, 2))"),
        array('dt' => 39, 'db' => "CAST(products_warehouses.commission_rate as decimal(64, 2))"),
        array('dt' => 40, 'db' => "CAST(products_warehouses.commission_agent_bonus as decimal(64, 2))"),
        array('dt' => 41, 'db' => "products_warehouses.production_date"),
        array('dt' => 42, 'db' => "IFNULL(CONCAT(products_warehouses.reserve_since_date, 
            ' - ',products_warehouses.reserve_till_date), '')"),
    );

    var $product_warehouses_column_names = [
        "_Id",
        "Article",
        "Product",
        "Brand",
        "Warehouse",
        "Quantity",
        "Units",
        'Number of Packs',
        'Total Weight',
        "Purchase Price",
        "Buy + Transport + Taxes",
        "Sell Price",
        "Dealer Price (-30%)",
        "Total Price",
        "Purchase Value + Expenses",
        "Purchase Price + Expenses",
        'Manager Order ID',
        'Manager',
        'Date of Order (Client)',
        'Status',
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
    ];

    var $where = 'products_warehouses.manager_order_id IS NULL';

    var $where_issue = '(((products_warehouses.manager_order_id IS NOT NULL AND '.
    'products_warehouses.reserve_since_date IS NULL) AND (products_warehouses.status_id = '.ON_STOCK.' OR '.
    'products_warehouses.status_id = '.EXPECTS_ISSUE.')) OR (products_warehouses.reserve_since_date IS NOT NULL AND '.
    'products_warehouses.status_id = '.EXPECTS_ISSUE.' AND products_warehouses.manager_order_id IS NOT NULL))';

    var $where_reserve = '(products_warehouses.manager_order_id IS NOT NULL AND '.
    'products_warehouses.reserve_since_date IS NOT NULL)';

    function getDTProductsForWarehouses($input, $warehouse_id = 0, $type, $printOpt, $tableName = '')
    {

//        $input['columns'][2]['search']['value'] = 'Admonter SP1020 Oak robust rustic ng t&g 15x 185x1850 mm';

        if ($warehouse_id) {
            $where = ["products_warehouses.warehouse_id = $warehouse_id"];
        } else {
            $where = ['products_warehouses.warehouse_id IS NOT NULL'];
        }
        $where[] = 'products_warehouses.is_deleted = 0';
        $where[] = "products_warehouses.status_id <> " . ISSUED;
        switch ($type) {
            case '':
                $where[] = $this->where;
                break;
            case 'issue':
                $where[] = $this->where_issue;
                break;
            case 'reserve':
                $where[] = $this->where_reserve;
                break;
        }

        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $where[] = "(orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                " client.sales_manager_id = ". $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id .
                ' OR products_warehouses.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
        }
        if ($this->user->permissions <= SALES_MANAGER_PERM) {
            $this->unLinkStrings($this->product_warehouses_columns, [27, 30, 33, 34]);
        }

        $columns = $this->getColumns($this->product_warehouses_columns, 'warehouse', $tableName);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->getColumns($this->product_warehouses_column_names,
                'warehouse', $tableName, true),
            'db_table' => $this->products_warehouses_table,
            'page' => 'warehouse',
            'table_name' => $tableName,
            'primary' => 'products_warehouses.item_id',
        ];

        if ($printOpt) {

            $printOpt['where'] = $where;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;

        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $where);
    }

    function getSelects($warehouse_id = 0, $table_id)
    {
        if (!$warehouse_id) {
            $where = ['products_warehouses.warehouse_id IS NOT NULL'];
        } else {
            $where = ["products_warehouses.warehouse_id = $warehouse_id"];
        }

        $where[] = "products_warehouses.status_id <> " . ISSUED;
        $where[] = 'products_warehouses.is_deleted = 0';
        switch ($table_id) {
            case 'table_warehouses_products':
                $where[] = $this->where;
                break;
            case 'table_warehouses_products_issue':
                $where[] = $this->where_issue;
                break;
            case 'table_warehouses_products_reserved':
                $where[] = $this->where_reserve;
                break;
        }

        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $where[] = "(orders.sales_manager_id = " . $this->user->user_id . ' OR '.
                " client.sales_manager_id = " . $this->user->user_id .
                " OR client.operational_manager_id = " . $this->user->user_id .
                ' OR products_warehouses.reserve_since_date IS NOT NULL OR orders.sales_manager_id IS NULL)';
        }
        if ($_SESSION['perm'] <= SALES_MANAGER_PERM) {
            $this->unLinkStrings($this->product_warehouses_columns, [27, 30, 33, 34]);
        }
        $columns = $this->getColumns($this->product_warehouses_columns, 'warehouse', 'table_warehouse');

        $ssp = $this->getSspComplexJson($this->products_warehouses_table, "products_warehouses.item_id",
            $columns, null, null, $where);

        $columnNames = $this->getColumns($this->product_warehouses_column_names, 'warehouse', 'table_warehouse', true);

        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['Id', 'Quantity', 'Purchase Price', 'Buy + Transport + Taxes', 'Sell Price',
            'Dealer Price (-30%)', 'Total Price'];

        if (!empty($rowValues)) {
            $selects = [];
            foreach ($rowValues as $product) {
                foreach ($product as $key => $value) {
                    if (!$value || $value == null)
                        continue;
                    $name = $columnNames[$key];
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
            return ['selectSearch' => $selects, 'filterSearchValues' => $rowValues];
        }
        return ['selectSearch' => [], 'filterSearchValues' => []];
    }

    function getModalProducts($input, $table_id)
    {
        $where = ['products.is_deleted = 0'];
        $columns = $this->getColumns($this->full_product_columns, 'warehouse', $table_id);
        $colNames = $this->getColumns($this->full_product_column_names, 'warehouse', $table_id, true);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $colNames,
            'db_table' => $this->full_products_table,
            'page' => 'warehouse',
            'table_name' => $table_id,
            'primary' => 'products.product_id',
        ];

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $where);
    }


    function addProductsWarehouse($products, $warehouse_id)
    {
//        if ($product_ids)
//            $product_ids = explode(',', $product_ids);
//        else
//            return false;

//        $amount = $amount !== 'null' ? $amount : 0;
//        $buy_price = $buy_price !== 'null' ? $buy_price : 0;

//        $itemIds = [];

        $items = [];
        foreach ($products as $product_id => $product) {

            $amount = isset($product['amount']) && $product['amount'] ? $product['amount'] : 0;
            $purchase_price = isset($product['purchase_price']) && $product['purchase_price'] ? $product['purchase_price'] : 0;
            $numberOfPacks = isset($product['number_of_packs']) && $product['number_of_packs'] ? $product['number_of_packs'] : 0;

            $items[] = $this->insert("INSERT INTO `order_items` (`product_id`, `warehouse_id`, `amount`, `purchase_price`,
              `status_id`, `number_of_packs`) VALUES ($product_id, $warehouse_id, $amount, $purchase_price, ".ON_STOCK.", $numberOfPacks)");
        }

        $this->addLog(LOG_ADD_TO_WAREHOUSE, ['warehouse_id' => $warehouse_id, 'items' => $items]);

//        $roles = new Roles();
//        if ($roles->getPageAccessAbilities('warehouse')['p']) {
//            $whereItems = join(',', $itemIds);
//            $where = "item_id IN ($whereItems)";
//            return $this->printDoc($warehouse_id, $where, 'warehouse');
//        }

//        $existing_pw = $this->getFirst("SELECT *
//                FROM products_warehouses
//                WHERE product_id = $product_id AND warehouse_id = $warehouse_id");
//        if ($existing_pw != null) {
//            $existing_amount = $existing_pw['amount'];
//            $new_new_amount = $existing_amount + $amount;
//            $buy_price = ($existing_pw['buy_price'] * $existing_amount + $buy_price * $amount) / $new_new_amount;
//            $buy_and_taxes = ($existing_pw['buy_and_taxes'] * $existing_amount + $buy_and_taxes * $amount) / $new_new_amount;
//            $sell_price = ($existing_pw['sell_price'] * $existing_amount + $sell_price * $amount) / $new_new_amount;
//            $dealer_price = ($existing_pw['dealer_price'] * $existing_amount + $dealer_price * $amount) / $new_new_amount;
//            $total_price = $existing_pw['total_price'] + $total_price;
//
//            $this->update("UPDATE products_warehouses
//                SET amount = $new_new_amount, buy_price = $buy_price, buy_and_taxes = $buy_and_taxes,
//                    sell_price = $sell_price, dealer_price = $dealer_price, total_price = $total_price
//                WHERE product_warehouse_id = ${existing_pw['product_warehouse_id']}");
//            return $existing_pw['product_warehouse_id'];
//        } else {
//            return $this->insert("INSERT INTO `products_warehouses` (`product_id`, `warehouse_id`, `amount`, `buy_price`, `buy_and_taxes`, `sell_price`, `dealer_price`, `total_price`)
//                    VALUES ($product_id, $warehouse_id, $amount, $buy_price, $buy_and_taxes, $sell_price, $dealer_price, $total_price)");
//        }
    }

    public function printLogDoc($logId)
    {
        if ($logId) {

            $log = $this->getFirst("SELECT logging.action, logging.info, logging.date, logging.user_id,
                      COUNT(type_log.log_id) as log_number FROM logging
                      LEFT JOIN logging AS type_log ON (type_log.action = logging.action AND type_log.log_id <= logging.log_id)
                      WHERE logging.log_id = $logId");
            if ($log) {
                $action = $log['action'];
                $logData = [
                    'merge' => [
                        'log_date' => $log['date'],
                        'log_id' => $log['log_number']
                    ]
                ];
                $info = json_decode($log['info'], true);
                $items = $info['items'];
                $items = implode(',', $items);
                $where = ['is_deleted = 0'];
                switch ($action) {
                    case LOG_ADD_TO_WAREHOUSE:
                        $warehouseId = $info['warehouse_id'];
                        $where[] = "item_id IN ($items)";
                        return $this->printDoc($warehouseId, $where, 'warehouse', $logData);
                    case LOG_DELIVERY_TO_WAREHOUSE:
                        $warehouseId = $info['warehouse_id'];
                        $where[] = "item_id IN ($items)";
                        return $this->printDoc($warehouseId, $where, 'truck_to_warehouse', $logData);
                    case LOG_RETURN_TO_WAREHOUSE:
                        $orderId = $this->getFirst("SELECT manager_order_id as id FROM order_items 
                            WHERE item_id = ${items[0]}")['id'];
                        $logData['order_id'] = $orderId;
                        $warehouseId = $info['warehouse_id'];
                        $where[] = "item_id IN ($items)";
                        return $this->printDoc($warehouseId, $where, 'return', $logData);
                    case LOG_ISSUE_FROM_WAREHOUSE:
                        $where[] = "item_id IN ($items)";
                        return $this->printDoc(false, $where, 'issue', $logData);
                    case LOG_ASSEMBLING_PRODUCT_WAREHOUSE:
                        $where[] = "item_id IN ($items)";
                        $logData['items_replace'] = $info['items_detail'];
                        $assProductId = $info['product'];
                        $product = $this->getFirst("SELECT * FROM products WHERE product_id = $assProductId");
                        $userId = $log['user_id'];
                        $user = $this->getFirst("SELECT * FROM users WHERE user_id = $userId");
                        $merge = [
                            'assemble_product_article' => $product['article'],
                            'assemble_product_name' => $this->getProductName($assProductId, $product),
                            'assemble_product_amount' => $info['product_amount'],
                            'assemble_product_units' => $product['units'],
                            'manager' => $user ? $user['last_name'] . ' ' . $user['first_name'] : '',
                        ];
                        $logData['merge'] = array_merge($logData['merge'], $merge);
                        return $this->printDoc(false, $where, 'assemble', $logData);
                    CASE LOG_CHANGE_WAREHOUSE:
                        $where[] = "item_id IN ($items)";
                        $oldWarehouse = $info['old_warehouse_id'];
                        $warehouse = $info['warehouse_id'];
                        $warehouses = $this->getFirst("SELECT new_warehouse.name as warehouse_to, 
                              old_warehouse.name as warehouse_from
                            FROM warehouses new_warehouse
                            LEFT JOIN warehouses old_warehouse ON (old_warehouse.warehouse_id = $oldWarehouse)
                            WHERE new_warehouse.warehouse_id = $warehouse");
                        $logData['merge'] = array_merge($logData['merge'], $warehouses);
                        return $this->printDoc(false, $where, 'change_warehouse', $logData);
                }
            }

        }
    }

    public function printDoc($warehouseId, $where, $type='', $log = [], $selectedString = '')
    {
        $fileName = $type;

        if (!$warehouseId) {
            $where[] = 'products_warehouses.warehouse_id IS NOT NULL';
        } else {
            $where[] = "products_warehouses.warehouse_id = $warehouseId";
        }

        $where[] = 'products_warehouses.is_deleted = 0';
        $where[] = "products_warehouses.item_id IN ($selectedString)";

        $where = implode(' AND ', $where);

        $orderItems = $this->getAssoc("SELECT * FROM order_items products_warehouses WHERE $where");

        if (!empty($orderItems)) {

            $order_id = null;
            foreach ($orderItems as $orderItem) {
                if (is_null($order_id)) {
                    $order_id = $orderItem['manager_order_id'];
                }
                if ($order_id !== $orderItem['manager_order_id']) {
                    return json_encode(['success' => 0, 'message' => 'All products must be from same order!']);
                }
            }

            $array = $this->getProductsDataArrayForDocPrint($orderItems, true);

            $products = $array['products'];
            $values = $array['values'];

            require dirname(__FILE__) . "/../../assets/PHPWord_CloneRow-master/PHPWord.php";
            $phpWord =  new PHPWord();
            $docFile = dirname(__FILE__) . "/../../docs/templates/$fileName.docx";

            $values['date'] = date('d.m.Y');
            $prodIds = [];
            foreach ($orderItems as $orderItem) {
                $prodIds[] = $orderItem['product_id'];
            }
            $values['product_id'] = join(', ', $prodIds);

            if (!empty($log)) {

                if (isset($log['items_replace']) && !empty($log['items_replace'])) {
                    $itemsReplace = $log['items_replace'];
                    $products = array_merge($products, $itemsReplace);
                }


                if (isset($log['order_id'])) {
                    $orderId = $log['order_id'];
                    $client = $this->getFirst("SELECT clients.*, legal_entities.visual_name as vis_ent,
                                              orders.visible_order_id as visible_order_id
                                                        FROM clients 
                                                        RIGHT JOIN orders ON (order_id = $orderId) 
                                                        LEFT JOIN legal_entities ON 
                                                        (legal_entities.legal_entity_id = orders.legal_entity_id)
                                                        WHERE clients.client_id = orders.client_id");
                    if ($client) {
                        $add[] = $client['name'];
                        if (!is_null($client['inn']))
                            $add[] = 'ИНН ' . $client['inn'];
                        if (!is_null($client['legal_address']))
                            $add[] = $client['legal_address'];
                        $values['client'] = join(', ', $add);
                        $values['visual_legal_entity_name'] = $client['vis_ent'];
                        $values['visible_order_id'] = $client['visible_order_id'];
                    }
                }

                if (isset($log['merge']))
                    $values = array_merge($values, $log['merge']);
            }


            $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
            $clientId = $order['client_id'];
            $client = $this->getFirst("SELECT * FROM clients WHERE client_id = $clientId");
            $add = [$client['final_name']];
            if (!is_null($client['inn']) && trim($client['inn']))
                $add[] = 'ИНН ' . trim($client['inn']);
            if (!is_null($client['legal_address']) && trim($client['legal_address']))
                $add[] = trim($client['legal_address']);
            if (!is_null($client['mobile_number']) && trim($client['mobile_number']))
                $add[] = 'тел.: ' . trim($client['mobile_number']);
            $values['client'] = join(', ', $add);

            $legalEntity = $this->getFirst("SELECT * FROM legal_entities 
                  WHERE legal_entity_id = ${order['legal_entity_id']}");
            $values['visual_legal_entity_name'] = $legalEntity['visual_name'];
//            $manager = $order['sales_manager_id'];
//            $user = $this->getFirst("SELECT * FROM users WHERE user_id = $manager");
//            $values['manager'] = $user ? $user['visual_name'] : '?';

            $values['visible_order_id'] = ($order['visible_order_id']) ? $order['visible_order_id'] : $order['order_id'];
            $values['order_date'] = date('Y-m-d', strtotime($order['start_date']));
            $values['date'] = date('Y-m-d');

            $templateProcessor = $phpWord->loadTemplate($docFile);

            $templateProcessor->cloneRow('TBL', $products);
            foreach ($values as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            $templateProcessor->save(dirname(__FILE__) . "/../../docs/ready/$fileName.docx");

            return "/docs/ready/$fileName.docx";
        }
    }

    function getPrices($warehouse_id)
    {

        $buyPrice = 0;
        $sellPrice = 0;
        $buyAndExpenses = 0;
        $dealerPrice = 0;

        $where = ($warehouse_id) ? "WHERE warehouse_id = $warehouse_id" : "WHERE warehouse_id IS NOT NULL";
        $where .= ' AND is_deleted = 0';
        $warehouseProducts = $this->getAssoc("SELECT * FROM order_items $where");
        if (!empty($warehouseProducts))
            foreach ($warehouseProducts as $warehouseProduct) {
                $amount = $warehouseProduct['amount'];
//                $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${warehouseProduct['product_id']}");
                $buyPrice += $warehouseProduct['purchase_price'] * $amount;
                $buyAndExpenses += $warehouseProduct['buy_and_taxes'] * $amount;
                $dealerPrice += $warehouseProduct['dealer_price'] * $amount;
                $sellPrice += $warehouseProduct['sell_price'] * $amount;
            }
        return [
            'buy' => $buyPrice,
            'buyAndExpenses' => $buyAndExpenses,
            'dealer' => $dealerPrice,
            'sellPrice' => $sellPrice,
        ];
    }

    public function updateItemField($warehouse_item_id, $field, $new_value)
    {
        $old_order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $warehouse_item_id");

        if ($field == 'number_of_packs' || $field == 'amount') {
            $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${old_order_item['product_id']}");
            switch ($field) {
                case 'number_of_packs':
                    $number_of_packs = floatval($new_value);
                    $amount = $number_of_packs * floatval($product['amount_in_pack']);
                    break;
                case 'amount':
                    $amount = floatval($new_value);
                    $number_of_packs = $amount / floatval($product['amount_in_pack']);
            }

            $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) * $amount / 100;
            $commission_agent_bonus = $old_order_item['commission_rate'] * $sellValue / 100;
            $manager_bonus = ($sellValue - $commission_agent_bonus) * $old_order_item['manager_bonus_rate'] / 100;

            $this->update("UPDATE order_items SET number_of_packs = $number_of_packs, amount = $amount, 
                commission_agent_bonus = $commission_agent_bonus, manager_bonus = $manager_bonus
                                    WHERE item_id = $warehouse_item_id");
            return true;
        }

        $result = $this->update("UPDATE `order_items` SET `$field` = '$new_value' WHERE item_id = $warehouse_item_id");

        if ($field == 'status_id' && $orderId = $old_order_item['manager_order_id']) {
            $this->updateItemsStatus($orderId);
        }

        if ($field == 'warehouse_id' && $result)
            $this->addLog(LOG_CHANGE_WAREHOUSE, ['items' => [$warehouse_item_id],
                'old_warehouse_id' => $old_order_item['warehouse_id'], 'warehouse_id' => $new_value]);

        return $result;
    }

    public function getDocuments($warehouse_id)
    {
        $docs = [
            [
                'href' => "/warehouse/print_doc?warehouse_id=$warehouse_id",
                'name' => 'Print'
            ],
        ];
        return $docs;
    }

    public function issueProducts($items)
    {
        if ($items) {
            $items = explode(',', $items);
            $successItems = [];
            foreach ($items as $item) {
                $result = $this->update("UPDATE order_items SET status_id = " . ISSUED . " WHERE item_id = $item");
                if ($result)
                    $successItems[] = $item;
            }
            $this->addLog(LOG_ISSUE_FROM_WAREHOUSE, ['items' => $successItems]);
            return true;
        }
    }

    public function discardProducts($items)
    {
        if ($items) {
            $warehouse_products = $this->getAssoc("SELECT * FROM order_items WHERE item_id IN ($items)");
            if (!empty($warehouse_products)) {
                foreach ($warehouse_products as $warehouse_product) {
                    $itemId = $warehouse_product['item_id'];
                    unset($warehouse_product['item_id']);
                    $names = $values = [];
                    foreach ($warehouse_product as $name => $value) {
                        $value = trim($value);
                        if (!$value)
                            continue;
                        $value = mysql_escape_string($value);
                        $names[] = "`$name`";
                        $values[] = "'$value'";
                    }
                    $names = implode(',', $names);
                    $values = implode(',', $values);
                    $result = $this->insert("INSERT INTO discarded_goods ($names) VALUES ($values)");
                    if ($result) {
                        $this->delete("DELETE FROM order_items WHERE item_id = $itemId");
                    } // TODO maybe add here update of orders for count and other
                }
                $this->addLog(LOG_DISCARD_FROM_WAREHOUSE, ['items' => explode(',', $items)]);
                return true;
            }
        }
    }

    public function getDTProductsAssembleSource($input, $items)
    {

        $columns = [
            array('dt' => 0, 'db' => 'products_warehouses.item_id'),
            array('dt' => 1, 'db' => "CONCAT('<a href=\"/product?id=',
                products.product_id,
                '\">', 
                    IFNULL(CONCAT(brands.name, ', '), ''),
                    IFNULL(CONCAT(products.collection, ', '), ''),
                    IFNULL(CONCAT(wood.name, ', '), ''),
                    IFNULL(CONCAT(grading.name, ', '), ''),
                    IFNULL(CONCAT(colors.name, ', '), ''),
                    IFNULL(CONCAT(products.texture, ', '), ''),
                    IFNULL(CONCAT(products.surface, ', '), ''),
                    IFNULL(CONCAT(products.thickness, 'x', products.width, 'x', products.length), ''),
                '</a>')"),
            array('dt' => 2, 'db' => "CONCAT('<span class=\"assemble-source-price\">', 
                CAST((IFNULL(products_warehouses.purchase_price, 0) + IFNULL(products_warehouses.import_VAT, 0) + 
                IFNULL(products_warehouses.import_brokers_price, 0) + IFNULL(products_warehouses.import_tax, 0) + 
                IFNULL(products_warehouses.delivery_price, 0)) as decimal(64, 2)),
                '</span>', ' ', products.purchase_price_currency)"),
            array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-assemble-amount\" data-pk=\"',
                products_warehouses.item_id, '\" data-name=\"assemble-amount\" data-value=\"',products_warehouses.amount,'\" 
                data-original-title=\"Enter Quantity\" data-max=\"', products_warehouses.amount, '\">', 
                products_warehouses.amount, '</a>',
                ' ', products.packing_type)"),
            array('dt' => 4, 'db' => "CONCAT('<span class=\"assemble-source-price-total\">', 
                CAST(((IFNULL(products_warehouses.purchase_price, 0) + IFNULL(products_warehouses.import_VAT, 0) + 
                IFNULL(products_warehouses.import_brokers_price, 0) + IFNULL(products_warehouses.import_tax, 0) + 
                IFNULL(products_warehouses.delivery_price, 0)) * products_warehouses.amount) as decimal(64, 2)), 
                '</span>', ' ', products.purchase_price_currency)"),
        ];

        $where = ["products_warehouses.item_id IN ($items)", 'products_warehouses.is_deleted = 0'];

        $table = $this->products_warehouses_table . " left join wood on products.wood_id = wood.wood_id " .
            "left join grading on products.grading_id = grading.grading_id ".
            "left join patterns on products.pattern_id = patterns.pattern_id ".
            "left join colors on products.color_id = colors.color_id ".
            "left join colors as colors2 on products.color2_id = colors2.color_id ";

        $this->sspComplex($table, "products_warehouses.item_id", $columns,
            $input, null, $where);
    }

    public function submitAssemble($assembleWarehouseProducts, $assembleProduct, $warehouseId)
    {

        if (!empty($assembleWarehouseProducts) && !empty($assembleProduct)) {

            $pks = array_keys($assembleWarehouseProducts);
            $pks = implode(',', $pks);
            $items = $this->getAssoc("SELECT * FROM order_items WHERE item_id IN ($pks)");
            $issuedProducts = [];
            $assembledSourcesDetail = [];
            foreach ($items as $item) {
                $itemId = $item['item_id'];
                $enteredAmount = $assembleWarehouseProducts[$itemId];
                $realAmount = $item['amount'];
                if ($realAmount >= $enteredAmount && $enteredAmount) {
                    unset($item['item_id']);
                    $item['status_id'] = ISSUED;
                    $item['amount'] = $enteredAmount;
                    $valuesArray = [];
                    $fieldsArray = [];
                    foreach ($item as $field => $value) {
                        $value = $value != "" ? $this->escape_string($value) : '';
                        if (!$value)
                            continue;
                        $fieldsArray[] = "$field";
                        $valuesArray[] = "'$value'";
                    }
                    $values = join(', ', $valuesArray);
                    $fieldsString = join(', ', $fieldsArray);
                    $id = $this->insert("INSERT INTO order_items ($fieldsString) VALUES ($values)");
                    if ($id) {
                        $newAmount = $realAmount - $enteredAmount;
                        $this->update("UPDATE order_items SET amount = $newAmount WHERE item_id = $itemId");
                        $issuedProducts[] = $id;
                        $assembledSourcesDetail['amount'][] = $enteredAmount;
                    }
                }
            }

            $this->addLog(LOG_ISSUE_FROM_WAREHOUSE, ['items' => $issuedProducts]);

            $pk = key($assembleProduct);
            $amount = $assembleProduct[$pk];

            $product = $this->getFirst("SELECT * FROM products WHERE product_id = $pk");
            $productPrice = $product['purchase_price'] != null ? $product['purchase_price'] : 0;

            $assembleItem = $this->insert("INSERT INTO order_items (`product_id`, `amount`, `warehouse_id`, `status_id`,
              `purchase_price`, sell_price) 
                VALUES ($pk, $amount, $warehouseId, ".ON_STOCK.", $productPrice, ${product['sell_price']})");

            if ($assembleItem)
                $this->addLog(LOG_ASSEMBLING_PRODUCT_WAREHOUSE, [
                        'items' => $issuedProducts,
                        'items_detail' => $assembledSourcesDetail,
                        'product' => $pk,
                        'product_amount' => $amount
                    ]);

        }
    }


}