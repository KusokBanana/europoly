<?php

include_once 'model_order.php';

class ModelTruck extends ModelOrder
{
    var $truck_columns = [
        array('dt' => 0, 'db' => "trucks_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
            IF(trucks_items.status_id < ".ON_STOCK.",
            CONCAT('<a data-href=\"/truck/put_to_the_warehouse?truck_item_id=', trucks_items.item_id, '\" 
                       class=\"put-to-warehouse\">
                            <span class=\'glyphicon glyphicon-home\' title=\'Put to Warehouse\'></span>
                            </a>'),
             ''),
            CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the item?\" 
                       href=\"/truck/delete_order_item?order_id=', trucks_items.truck_id, '&order_item_id=', 
                        trucks_items.item_id, '\"
                        class=\"table-confirm-btn\" data-placement=\"right\" data-popout=\"true\" 
                        data-singleton=\"true\">
                            <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                        </a>'),
        '</div>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/product?id=',
                trucks_items.product_id,
                '\">', IFNULL(products.visual_name, 'Enter Visual Name!'), '</a>')"),
        array('dt' => 3, 'db' => "CONCAT(CAST(trucks_items.amount as decimal(64,3)), ' ', IFNULL(products.units, ''))"),
//        array('dt' => 2, 'db' => "IF(trucks_items.status_id > 8,
//                CONCAT(CAST(trucks_items.amount as decimal(64,3)), ' ', IFNULL(products.units, '')),
//                CONCAT('<a href=\"javascript:;\" class=\"x-editable x-amount\" data-pk=\"',
//                trucks_items.item_id,
//                '\" data-name=\"amount\" data-value=\"',
//                IFNULL(trucks_items.amount, ''),
//                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Quantity\">',
//                    IFNULL(CONCAT(CAST(trucks_items.amount as decimal(64, 3)), ' ', products.units), ''),
//                '</a>'))"),
        array('dt' => 4, 'db' => "CONCAT(CAST(trucks_items.number_of_packs as decimal(64, 3)), ' ', 
            IFNULL(products.packing_type, ''))"),
//        array('dt' => 3, 'db' => "IF(trucks_items.status_id > 8,
//                CONCAT(CAST(trucks_items.number_of_packs as decimal(64, 3)), ' ', IFNULL(products.packing_type, '')),
//                CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
//                trucks_items.item_id,
//                '\" data-name=\"number_of_packs\" data-value=\"',
//                IFNULL(trucks_items.number_of_packs, ''),
//                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Number of Packs\">',
//                    CONCAT(CAST(trucks_items.number_of_packs as decimal(64, 3)), ' ', IFNULL(products.packing_type, '')),
//                '</a>'))"),
        array('dt' => 5, 'db' => "IFNULL(CAST(trucks_items.purchase_price as decimal(64, 2)), '')"),
        array('dt' => 6, 'db' => "IFNULL(CAST(trucks_items.purchase_price * trucks_items.amount as decimal(64, 2)), '')"),
        array('dt' => 7, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                trucks_items.item_id,
                '\" data-name=\"status_id\" data-value=\"',
                trucks_items.status_id,
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Choose Item Status\">',
                    status.name,
                '</a>')"),
        array('dt' => 8, 'db' => "CAST(products.weight * trucks_items.amount as decimal(64, 3))"),
        array('dt' => 9, 'db' => "CONCAT(orders.downpayment_rate, ' %')"),
        array('dt' => 10, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 11, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 12, 'db' => "CONCAT('<a href=\"/order?id=',
                trucks_items.manager_order_id,
                '\">', trucks_items.manager_order_id,
                 IF(trucks_items.reserve_since_date IS NULL, '', 
                 (CONCAT(' (reserved ', trucks_items.reserve_since_date, ')'))), '</a>')"),
        array('dt' => 13, 'db' => "CONCAT('<a href=\"/suppliers_order?id=',
                trucks_items.supplier_order_id,
                '\">', trucks_items.supplier_order_id, '</a>')"),
        array('dt' => 14, 'db' => "clients.final_name"),
        array('dt' => 15, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-import_VAT\" data-pk=\"',
                trucks_items.item_id,
                '\" data-name=\"import_VAT\" data-value=\"',
                IFNULL(CAST(trucks_items.import_VAT as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Import VAT\">',
                    IFNULL(CAST(trucks_items.import_VAT as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 16, 'db' => "CONCAT('<a href=\"javascript:;\" 
                class=\"x-editable x-import_brokers_price\" data-pk=\"',
                trucks_items.item_id,
                '\" data-name=\"import_brokers_price\" data-value=\"',
                IFNULL(CAST(trucks_items.import_brokers_price as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Import Brokers Price\">',
                    IFNULL(CAST(trucks_items.import_brokers_price as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 17, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-import_tax\" data-pk=\"',
                trucks_items.item_id,
                '\" data-name=\"import_tax\" data-value=\"',
                IFNULL(CAST(trucks_items.import_tax as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Customs Price\">',
                    IFNULL(CAST(trucks_items.import_tax as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 18, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-delivery_price\" data-pk=\"',
                trucks_items.item_id,
                '\" data-name=\"delivery_price\" data-value=\"',
                IFNULL(CAST(trucks_items.delivery_price as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Delivery Price\">',
                    IFNULL(CAST(trucks_items.delivery_price as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 19, 'db' => "suppliers_orders.production_date"),
    ];

    var $suppliers_orders_column_names = [
        'Supplier Order ID',
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
        'Total Purchase Price',
        'Sell Price / Unit',
        'Total Sell Price',
        'Downpayment',
        'Downpayment rate',
        'Client\'s expected date of issue',
    ];

    var $truck_column_names = [
        'ID',
        'Actions',
        'Product',
        'Quantity',
        '# of packs ',
        'Purchase Price ',
        'Purchase Value ',
        'Status',
        'Weight',
        'Downpayment rate, % ',
        'Client\'s expected date of issue',
        'Manager',
        'Managers order id ',
        'Suppliers order id ',
        'Client',
        'Import VAT ',
        'Import Brokers Price ',
        'Import Tax ',
        'Delivery Price ',
        'Production Date'
    ];

    function getDTTrucks($truck_id, $input)
    {
        $trucks_table = 'order_items as trucks_items
            left join products on trucks_items.product_id = products.product_id ' . $this->full_products_table_addition .' 
            left join trucks on trucks.id = trucks_items.truck_id
            left join suppliers_orders on suppliers_orders.order_id = trucks_items.supplier_order_id
            left join orders on trucks_items.manager_order_id = orders.order_id
            left join clients on orders.client_id = clients.client_id
            left join items_status as status on trucks_items.status_id = status.status_id
            left join users as managers on orders.sales_manager_id = managers.user_id';

        $roles = new Roles();
        $columns = $roles->returnModelColumns($this->truck_columns, 'truck');

        $this->sspComplex($trucks_table, "trucks_items.item_id", $columns,
            $input, null, "trucks_items.truck_id = $truck_id");
    }

    public function getTruckStatus($truck_id)
    {
        $status = $this->getFirst("SELECT status.name as statusName
          FROM trucks as t 
          LEFT JOIN items_status as status ON t.status_id = status.status_id 
          WHERE t.id = $truck_id");
        return $status ? $status['statusName'] : '';
    }

    function addTruckItem($products, $truck_id = 0)
    {
        // Если машина новая
        if (!$truck_id) {
            $this->insert("INSERT INTO trucks (supplier_departure_date) VALUES (NOW())");
            $truck_id = $this->insert_id;
        }
        if ($truck_id) {
            $order_items_count = 0;
            foreach ($products as $order_item_id) {
                $this->update("UPDATE order_items SET status_id = ".ON_THE_WAY.", truck_id = $truck_id WHERE item_id = $order_item_id");
                $order_items_count++;
            }
            // Обновим количество товаров
            $this->update("UPDATE trucks 
                              SET truck_items_count = truck_items_count + $order_items_count 
                              WHERE id = $truck_id");
            $this->updateItemsStatus($truck_id);
        }
        return $truck_id;
    }

    function getDTOrderItems($order_id, $input)
    {
        $table = $this->full_products_table . ' join trucks_items on products.product_id = trucks_items.product_id';

        $this->sspComplex($table, "trucks_items.truck_item_id", $this->truck_columns,
            $input, null, "trucks_items.truck_id = $order_id");
    }

    function getSSPData($type = 'general')
    {
        $ssp = ['page' => $this->page];

        switch ($type) {
            case 'modal_suppliers_orders':
                require_once 'model_suppliers_orders.php';
                $model = new ModelSuppliers_orders();
                $ssp = $model->getSSPData($type);
                $ssp['page'] = $this->page;
                break;
        }

        return $ssp;
    }

    public function getTableData($type = 'general', $opts = [])
    {
        $data = $this->getSSPData($type);

        switch ($type) {
            case 'modal_suppliers_orders':
                $model = new ModelSuppliers_orders();
                $selects = $model->getSelects($data);
                break;
        }

        return array_merge($data, $selects);
    }

    function getDTSuppliersOrdersToTruck($input)
    {
        $ssp = $this->getSSPData('modal_suppliers_orders');

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, $ssp['where']);

    }

    function putToTheWarehouse($items, $warehouse_id)
    {
        if (!empty($items)) {

            $ids = [];

            foreach ($items as $item_id => $amount) {
                $item = $this->getFirst("SELECT item_id, status_id, warehouse_arrival_date, amount FROM order_items 
                  WHERE item_id = $item_id AND status_id = " . ON_THE_WAY . " AND warehouse_arrival_date IS NULL AND 
                  amount > 0");
                if (floatval($item['amount']) > floatval($amount)) {
                    $item_id = $this->split($item_id, $amount);
                    if (!$item_id)
                        continue;
                } elseif (floatval($item['amount']) < floatval($amount)) {
                    continue;
                }

                $ids[] = $item_id;
                $this->putItemToWarehouse($item_id, $warehouse_id, true);
            }

            if (!empty($ids)) {
                $this->addLog(LOG_DELIVERY_TO_WAREHOUSE, ['items' => $ids, 'warehouse_id' => $warehouse_id]);
            }

        }
//        return $this->printDoc($truckId);
    }

    function getItemsToPutToWarehouse($truck_id, $truck_item_id)
    {

        if ($truck_id) {
            $items = $this->getAssoc("SELECT order_items.amount as amount, order_items.item_id, " .
                "products.visual_name as name, order_items.truck_id, order_items.status_id as status_id " .
                "FROM order_items " .
                "LEFT JOIN products ON (order_items.product_id = products.product_id) " .
                "WHERE order_items.truck_id = $truck_id AND order_items.status_id = " . ON_THE_WAY);

            if (!empty($items)) {
                return json_encode($items);
            } else {
                return json_encode(['success' => 0, 'message' => 'There are no items with status "On the Way"']);
            }
        } elseif ($truck_item_id) {
            $item = $this->getFirst("SELECT order_items.amount as amount, order_items.item_id, " .
                "products.visual_name as name, order_items.status_id as status_id " .
                "FROM order_items " .
                "LEFT JOIN products ON (order_items.product_id = products.product_id) " .
                "WHERE order_items.item_id = $truck_item_id AND order_items.status_id = " . ON_THE_WAY);
            if ($item) {
                return json_encode([$item]);
            } else {
                return json_encode(['success' => 0, 'message' => 'Item is incorrect']);
            }
        }

    }

    public function printDoc($truckId, $type = '')
    {
        $truckItems = $this->getAssoc("SELECT * FROM order_items WHERE truck_id = $truckId");
        $fileName = 'truck_to_warehouse';

        if (!empty($truckItems)) {
            $array = $this->getProductsDataArrayForDocPrint($truckItems, true);

            $products = $array['products'];
            $values = $array['values'];

            require dirname(__FILE__) . "/../../assets/PHPWord_CloneRow-master/PHPWord.php";
            $phpWord =  new PHPWord();
            $docFile = dirname(__FILE__) . "/../../docs/templates/$fileName.docx";

            $templateProcessor = $phpWord->loadTemplate($docFile);

            $templateProcessor->cloneRow('TBL', $products);
            foreach ($values as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            $templateProcessor->save(dirname(__FILE__) . "/../../docs/ready/$fileName.docx");

            return "/docs/ready/$fileName.docx";
        }
    }

    function putItemToWarehouse($itemId, $warehouse_id, $isLogged = false)
    {
        if (!$itemId)
            return false;

        $truckItem = $this->getFirst("SELECT product_id, amount, import_tax, import_brokers_price, import_VAT, truck_id 
                          FROM order_items WHERE item_id = $itemId AND status_id = " . ON_THE_WAY);

        $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${truckItem['product_id']}");

        $totalPrice = $truckItem['amount'] * $product['purchase_price'];

        $buyAndExpenses = $truckItem['import_tax'] + $truckItem['import_brokers_price'] + $truckItem['import_VAT'];

        $buyAndExpenses *= $truckItem['amount'];

        $this->update("UPDATE order_items SET warehouse_id = $warehouse_id, total_price = $totalPrice,
 	      buy_and_taxes = $buyAndExpenses, warehouse_arrival_date = NOW(), status_id = ".ON_STOCK." WHERE item_id = $itemId");

        $this->updateItemsStatus($truckItem['truck_id']);

        if (!$isLogged)
            $this->addLog(LOG_DELIVERY_TO_WAREHOUSE, ['items' => [$itemId]]);

        return true;
    }

    public function getOrder($order_id)
    {
        return $this->getById('trucks', 'id', $order_id);
    }

    function changeStatus($order_id, $status)
    {
        $this->update("UPDATE trucks SET status_id = $status WHERE id = $order_id");
    } // here

    function addOrderItem($truck_id, $product_ids)
    {
        $count = 0;
        foreach ($product_ids as $product_id) {
            $this->update("UPDATE order_items SET status_id = ".ON_THE_WAY.", truck_id = $truck_id WHERE item_id = $product_id");
            $count++;
        }
        $this->update("UPDATE trucks
                SET truck_items_count = truck_items_count + $count
                WHERE id = $truck_id");
        $this->updateItemsStatus($truck_id);

    }

    function deleteOrderItem($order_id, $order_item_id)
    {
        $this->update("UPDATE order_items SET status_id = ".DRAFT_FOR_SUPPLIER.", truck_id = null, import_brokers_price = null,
                      import_VAT = null, delivery_price = null, import_tax = null, warehouse_arrival_date = null
                      WHERE item_id = $order_item_id");

        $this->update("UPDATE trucks 
                SET truck_items_count = truck_items_count - 1
                WHERE id = $order_id");

        $this->updateItemsStatus($order_id);

    }

    public function updateField($order_id, $field, $new_value)
    {
        $result = $this->update("UPDATE `trucks` SET `$field` = '$new_value' WHERE id = $order_id");

        return $result;
    }

    public function updateItemField($order_item_id, $field, $new_value)
    {
        $old_order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $order_item_id");

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
            if ($old_order_item['amount'] > $amount) {
                $newAmount = $old_order_item['amount'] - $amount;
                $valuesArray = [];
                $fieldsArray = [];
                foreach ($old_order_item as $field => $value) {
                    if ($field == 'item_id' || $field == 'truck_id')
                        continue;
                    $value = $value != "" ? $this->escape_string($value) : '';
                    if ($field == 'amount')
                        $value = $newAmount;
                    if ($field == 'number_of_packs')
                        $value = $newAmount / floatval($product['amount_in_pack']);
                    if ($field == 'status_id')
                        $value = CONFIRMED_BY_SUPPLIER;
                    if ($field == 'manager_order_id') {
                        if ($value == null)
                            continue;
                    }

                    if ($field == 'reserve_since_date' || $field == 'reserve_till_date')
                        continue;

                    $fieldsArray[] = "$field";
                    $valuesArray[] = "'$value'";
                }
                $values = join(', ', $valuesArray);
                $fieldsString = join(', ', $fieldsArray);
                $newId = $this->insert("INSERT INTO order_items ($fieldsString) VALUES ($values)");
                if ($old_order_item['manager_order_id']) {
                    $sellValueOld = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) * $amount / 100;
                    $sellValueNew = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) * $newAmount / 100;

                    $commission_agent_bonusOld = $old_order_item['commission_rate'] * $sellValueOld / 100;
                    $commission_agent_bonusNew = $old_order_item['commission_rate'] * $sellValueNew / 100;
                    $manager_bonusOld = ($sellValueOld - $commission_agent_bonusOld) * $old_order_item['manager_bonus_rate'] / 100;
                    $manager_bonusNew = ($sellValueNew - $commission_agent_bonusNew) * $old_order_item['manager_bonus_rate'] / 100;

                    $this->update("UPDATE order_items SET commission_agent_bonus = $commission_agent_bonusOld, 
                      manager_bonus = $manager_bonusOld WHERE item_id = $order_item_id");
                    $this->update("UPDATE order_items SET commission_agent_bonus = $commission_agent_bonusNew, 
                      manager_bonus = $manager_bonusNew WHERE item_id = $newId");

                }
                return $this->update("UPDATE order_items SET number_of_packs = $number_of_packs, amount = $amount
                                    WHERE item_id = $order_item_id");
            }
           return false;
        }

        $result = $this->update("UPDATE `order_items` SET `$field` = '$new_value' WHERE item_id = $order_item_id");

        if ($field == 'status_id') {
//        Изменим статус самого объекта
            $this->updateItemsStatus($old_order_item['truck_id']);
        }

        return $result;
    }


    public function updateItemsStatus($truckId)
    {
        $status = $this->getFirst("SELECT status_id FROM order_items WHERE  
                                    status_id = (SELECT MIN(status_id) FROM order_items WHERE truck_id = $truckId)");
        $truckStatus = $status ? $status['status_id'] : ON_THE_WAY;
        $this->update("UPDATE trucks 
                SET status_id = $truckStatus WHERE id = $truckId");
    }

    public function getStatusList()
    {
        $statusList = parent::getStatusList();
        return array_slice($statusList, 7, 5);
    }

    public function getDelivery($truck_id)
    {
        if (!$truck_id)
            return [];

        $transportOfCurrentTruck = $this->getFirst("SELECT transportation_company_id FROM trucks 
                                                      WHERE id = $truck_id");
        $truckItems = $this->getAssoc("SELECT delivery_price FROM order_items WHERE truck_id = $truck_id");
        $deliveryPrice = 0;
        $nameOfCurrentTransport = '';
        $transportList = [];
        if (!empty($truckItems))
            foreach ($truckItems as $truckItem) {
                $deliveryPrice += $truckItem['delivery_price'];
            }
        $transports = $this->getAssoc("SELECT transportation_company_id as id, name FROM transportation_companies");
        if (!empty($transports))
            foreach ($transports as $transport) {
                $item = new stdClass();
                if ($transportOfCurrentTruck['transportation_company_id'] == $transport['id'])
                    $nameOfCurrentTransport = $transport['name'];
                $item->value = $transport['id'];
                $item->text = $transport['name'];
                $transportList[] = $item;
            }

        return ['price' => $deliveryPrice, 'list' => $transportList, 'name' => $nameOfCurrentTransport];
    }

    public function getCustoms($truck_id)
    {
        if (!$truck_id)
            return [];

        $customsOfCurrentTruck = $this->getFirst("SELECT custom_id FROM trucks 
                                                      WHERE id = $truck_id");
        $truckItems = $this->getAssoc("SELECT import_tax, import_VAT, import_brokers_price FROM order_items 
                                          WHERE truck_id = $truck_id");
        $customPrice = 0;
        $nameOfCurrentCustom = '';
        $customsList = [];
        if (!empty($truckItems))
            foreach ($truckItems as $truckItem) {
                $customPrice += $truckItem['import_tax'] + $truckItem['import_VAT'] + $truckItem['import_brokers_price'];
            }
        $customs = $this->getAssoc("SELECT custom_id as id, name FROM customs");
        if (!empty($customs))
            foreach ($customs as $custom) {
                $item = new stdClass();
                if ($customsOfCurrentTruck == $custom['id'])
                    $nameOfCurrentCustom = $custom['name'];
                $item->value = $custom['id'];
                $item->text = $custom['name'];
                $customsList[] = $item;
            }

        return ['price' => $customPrice, 'list' => $customsList, 'name' => $nameOfCurrentCustom];
    }

    function getSums($truck_id)
    {
        $truckItems = $this->getAssoc("SELECT product_id, number_of_packs, sell_price, amount, purchase_price, amount
                        FROM order_items WHERE truck_id = $truck_id");

        return parent::getSums($truckItems);
    }

    public function getDocuments($truck_id)
    {
        $docs = [
//            [
//                'href' => "/truck/print_doc?truck_id=$truck_id",
//                'name' => 'Print'
//            ],
        ];
        return $docs;
    }
}