<?php

class ModelOrder extends Model
{
    function __construct()
    {
        $this->connect_db();
    }

    function getDTOrderItems($order_id, $input)
    {
        $columns = [
            array('dt' => 0, 'db' => "order_items.order_item_id"),
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
            array('dt' => 2, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-amount\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(order_items.amount, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(order_items.amount, ' ', products.units), ''),
                '</a>')"),
            array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(order_items.number_of_packs, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(order_items.number_of_packs, ''),
                '</a>')"),
            array('dt' => 4, 'db' => "CONCAT(IF(products.units = 'm2' AND products.length NOT LIKE '%-%' 
                                                                        AND products.width NOT LIKE '%-%',
                                        IF(products.width = NULL, 'Width undefined', 
                                        IF(products.length = NULL, 'Length undefined', 
                                            (order_items.amount * 1000 * 1000) / (products.width * products.length))
                                            ), 'n/a'), '')"), // TODO проверка на число, не интервал!
            array('dt' => 5, 'db' => "IFNULL(CAST(order_items.purchase_price as decimal(64, 2)), '')"),
            array('dt' => 6, 'db' => "IFNULL(CAST(order_items.purchase_price * order_items.amount as decimal(64, 2)), '')"),
            array('dt' => 7, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"sell_price\" data-value=\"',
                IFNULL(CAST(order_items.sell_price as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Sell Price, %\">',
                    IFNULL(CAST(order_items.sell_price as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 8, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-discount_rate\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"discount_rate\" data-value=\"',
                IFNULL(order_items.discount_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Discount Rate, %\">',
                    IFNULL(CONCAT(order_items.discount_rate, '%'), ''),
                '</a>')"),
            array('dt' => 9, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"reduced-price\" data-value=\"',
                IFNULL(CAST(order_items.sell_price * (100 - order_items.discount_rate)/100 as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Reduced Price\">',
                    IFNULL(CAST(order_items.sell_price * (100 - order_items.discount_rate)/100 as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 10, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"sell-value\" data-value=\"',
                IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Sell Value\">',
                    IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 11, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_rate\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"commission_rate\" data-value=\"',
                IFNULL(order_items.commission_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Commission Rate\">',
                    IFNULL((CONCAT(order_items.commission_rate, '%')), ''),
                '</a>')"),
            array('dt' => 12, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_agent_bonus\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"commission_agent_bonus\" data-value=\"',
                IFNULL(order_items.commission_agent_bonus, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Commission Agent Bonus\">',
                    IFNULL(order_items.commission_agent_bonus, ''),
                '</a>')"),
            array('dt' => 13, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus_rate\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"manager_bonus_rate\" data-value=\"',
                IFNULL(order_items.manager_bonus_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus Rate, %\">',
                    IFNULL(CONCAT(order_items.manager_bonus_rate, '%'), ''),
                '</a>')"),
            array('dt' => 14, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"manager_bonus\" data-value=\"',
                IFNULL(order_items.manager_bonus, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus\">',
                    IFNULL(order_items.manager_bonus, ''),
                '</a>')"),
            array('dt' => 15, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"item_status\" data-value=\"',
                IFNULL(order_items.item_status, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    IFNULL(order_items.item_status, ''),
                '</a>')"),
            array('dt' => 16, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                    IF(order_items.item_status = 'Draft',
                    CONCAT('<a href=\"/order/hold?order_item_id=', order_items.order_item_id,
                            '\" onclick=\"return confirm(\'Are you sure to hold the item?\')\">
                                <span class=\'glyphicon glyphicon-star\' title=\'Hold Item\'></span>
                            </a>'), 
                    ''),
                    IF(order_items.item_status = 'Draft' OR order_items.item_status = 'Hold', 
                        CONCAT('<a href=\"/order/reserve?order_item_id=', order_items.order_item_id, '&action=get_info',
                                '\" class=\"reserve-product-btn\" data-id=\"', order_items.order_item_id, '\">
                                    <span class=\'glyphicon glyphicon-heart\' title=\'Reserve Item\'></span>
                                </a>',
                                '<a href=\"/order/send_to_logist?order_item_id=', order_items.order_item_id,
                                '\" onclick=\"return confirm(\'Are you sure to send to logist the item?\')\">
                                    <span class=\'glyphicon glyphicon-download-alt\' title=\'Send to Logist\'></span>
                                </a>',
                                '<a href=\"/order/delete_order_item?order_id=', order_items.order_id, 
                                '&order_item_id=', order_items.order_item_id,
                                '\" onclick=\"return confirm(\'Are you sure to delete the item?\')\">
                                    <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                </a>'), 
                        ''),
                    IF(order_items.item_status = 'On Stock',
                        CONCAT('<a href=\"/order/issue?order_item_id=', order_items.order_item_id,
                                '\" onclick=\"return confirm(\'Are you sure to create issue for the item?\')\">
                                    <span class=\'fa fa-share\' title=\'Issue\'></span>
                                </a>'),
                        ''),
                '</div>')"),
        ];

        $table = $this->full_products_table . ' join order_items on products.product_id = order_items.product_id';
        return $this->sspComplex($table, "order_items.order_item_id", $columns, $input, null, "order_items.order_id = $order_id");
    }

    function changeStatus($order_id, $status)
    {
        $this->update("UPDATE orders SET order_status = '$status' WHERE order_id = $order_id");
    }

    function cancelOrder($order_id, $cancel_reason)
    {
        $this->update("UPDATE orders SET order_status = 'Cancelled', cancel_reason = '$cancel_reason' WHERE order_id = $order_id");
    }

    function deleteCommissionAgent($order_id)
    {
        $this->update("UPDATE orders SET commission_agent_id = NULL, commission_rate = 0, total_commission = 0, commission_status = null WHERE order_id = $order_id");
    }

    function addOrderItem($order_id, $product_ids)
    {
        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
        $client = $this->getFirst("SELECT * FROM clients WHERE client_id = ${order['client_id']}");
        $sales_manager = $this->getFirst("SELECT * FROM users WHERE user_id = ${order['sales_manager_id']}");
        $manager_bonus_rate = isset($sales_manager['manager_bonus_rate']) && $sales_manager['manager_bonus_rate'] != "" ? $sales_manager['manager_bonus_rate'] : 0;
        $price_field_name = $client['type'] == 'Dealer' ? 'dealer_price' : 'purchase_price';
        $discount_rate = $client['type'] == 'Dealer' ? 30 : 0;
        foreach ($product_ids as $product_id) {
            $product = $this->getFirst("SELECT * FROM products WHERE product_id = $product_id");
//            $number_of_packs = $product['amount_in_pack'] != null ? 0 : 1;
            $number_of_packs = 0;

            $productPrice = $product[$price_field_name] != null ? $product[$price_field_name] : 0;
            $totalPrice = $order['total_price'] + $productPrice;
            $this->insert("INSERT INTO order_items (order_id, product_id, purchase_price, amount, number_of_packs, total_price, discount_rate, reduced_price, manager_bonus_rate, manager_bonus, item_status, sell_price)
                VALUES ($order_id, $product_id, $productPrice, 0, $number_of_packs, 0, $discount_rate, 0, 
                $manager_bonus_rate, 0, 'Draft', ${product['sell_price']})");
            $order_items_count = $order['order_items_count'] + 1;
            $this->update("UPDATE orders 
                SET order_items_count = $order_items_count, total_price = $totalPrice
                WHERE order_id = $order_id");
        }
        $this->updateItemsStatus($order_id);
    }

    function deleteOrderItem($order_id, $order_item_id)
    {
        $order_item = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $order_item_id");
        $this->delete("DELETE FROM order_items WHERE order_item_id = $order_item_id");

        $this->updateItemsStatus($order_id);

        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
        $order_items_count = $order['order_items_count'] - 1;
        $amount = $order_item['amount'] ? $order_item['amount'] : 1;
        $totalPrice = $order['total_price'] - $order_item['purchase_price'] * $amount;
        $manager_bonus = $order['manager_bonus_rate'] * $totalPrice / 100;
        $total_commission = $order['commission_rate'] * $totalPrice / 100;
        $this->update("UPDATE orders 
                SET order_items_count = $order_items_count, total_price = $totalPrice, manager_bonus = $manager_bonus, total_commission = $total_commission
                WHERE order_id = $order_id");

    }

    public function updateField($order_id, $field, $new_value)
    {
        $old_order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
        $result = $this->update("UPDATE `orders` SET `$field` = '$new_value' WHERE order_id = $order_id");
        $new_order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");

        $total_price = $old_order['total_price'] - $old_order['special_expenses'] + $new_order['special_expenses'];
        $total_commission = $new_order['commission_rate'] * $total_price / 100;
        $this->update("UPDATE orders 
                SET total_price = $total_price, total_commission = $total_commission
                WHERE order_id = $order_id");

        return $result;
    }

    public function updateItemField($order_item_id, $field, $new_value)
    {
        $old_order_item = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $order_item_id");
        $orderId = $old_order_item['order_id'];

        if ($field == 'reduced-price' || $field == 'sell-value') {
            switch ($field) {
                case 'reduced-price':
                    $discountRate = 100 - $new_value / $old_order_item['sell_price'];
                    break;
                case 'sell-value':
                    $amount = ($old_order_item['amount'] ? $old_order_item['amount'] : 1);
                    $discountRate = 100 - $new_value / ($old_order_item['sell_price'] * $amount);
                    break;
            }
            $sellValue = ($old_order_item['sell_price'] * (100 - $discountRate)) * $old_order_item['amount'];
            $commission_agent_bonus = $old_order_item['commission_rate'] * $sellValue;
            $manager_bonus = ($sellValue - $commission_agent_bonus) * $old_order_item['manager_bonus_rate'];

            $this->update("UPDATE `order_items` SET discount_rate = $discountRate, 
                commission_agent_bonus = $commission_agent_bonus, manager_bonus = $manager_bonus
                      WHERE order_item_id = $order_item_id");
            $this->updateOrderPrice($orderId);
            return true;
        }
        if ($field == 'commission_agent_bonus' || $field == 'commission_rate') {
            switch ($field) {
                case 'commission_agent_bonus':
                    // Update Sell Price
                    $sellPrice = $new_value / ($old_order_item['amount'] * $old_order_item['commission_rate'] * (100 - $old_order_item['discount_rate']));
//                    $sellValue = ($sellPrice * (100 - $old_order_item['discount_rate'])) * $old_order_item['amount'];
                    $this->update("UPDATE `order_items` SET sell_price = $sellPrice
                          WHERE order_item_id = $order_item_id");
                    break;
                case 'commission_rate':
                    $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) *
                        $old_order_item['amount'];
                    $commission_agent_bonus = $sellValue * $new_value;
                    $this->update("UPDATE `order_items` SET commission_agent_bonus = $commission_agent_bonus 
                      WHERE order_item_id = $order_item_id");
                    break;
            }
        }
        if ($field == 'manager_bonus' || $field == 'manager_bonus_rate') {
            $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) * $old_order_item['amount'];
            switch ($field) {
                case 'manager_bonus': // TODO not correct
                    $commission_agent_bonus =  $sellValue - $new_value / $old_order_item['manager_bonus_rate'];
                    $this->update("UPDATE `order_items` SET manager_bonus = $new_value
                      WHERE order_item_id = $order_item_id");
                    $this->updateItemField($order_item_id, 'commission_agent_bonus', $commission_agent_bonus);
                    $this->updateOrderPrice($order_item_id);
                    return true;
//                    break;
                case 'manager_bonus_rate':
                    $manager_bonus = ($sellValue - $old_order_item['commission_agent_bonus']) * $new_value;
                    $this->update("UPDATE `order_items` SET manager_bonus = $manager_bonus 
                      WHERE order_item_id = $order_item_id");
                    break;
            }
        }
//        if ($field == 'amount' || $field == 'sell_price') {
//            $amount = ($field == 'amount') ? $new_value : $old_order_item['amount'];
//            $sellPrice = ($field == 'sell_price') ? $new_value : $old_order_item['sell_price'];
//
//            $sellValue = ($sellPrice * (100 - $old_order_item['discount_rate'])) * $amount;
//            $commission_agent_bonus = $old_order_item['commission_rate'] * $sellValue;
//            $manager_bonus = ($sellValue - $commission_agent_bonus) * $old_order_item['manager_bonus_rate'];
//            $this->update("UPDATE `order_items` SET manager_bonus = $manager_bonus,
//                      commission_agent_bonus = $commission_agent_bonus
//                      WHERE order_item_id = $order_item_id");
//        }

        // TODO fix it and uncomment
//        if ($product['amount_in_pack'] != null) {
//            if ($old_order_item['amount'] != $new_order_item['amount']) {
//                $number_of_packs = ($new_order_item['amount'] / $product['amount_in_pack']);
////                $number_of_packs = ceil($new_order_item['amount'] / $product['amount_in_pack']);
//            } else {
//                $number_of_packs = $new_order_item['number_of_packs'];
//            }
//            $amount = $number_of_packs * $product['amount_in_pack'];
//        } else {
//            $number_of_packs = 1;
//            $amount = $new_order_item['amount'];
//        }

        $this->update("UPDATE `order_items` SET `$field` = '$new_value' WHERE order_item_id = $order_item_id");

        $new_order_item = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $order_item_id");

        $sellValue = ($new_order_item['sell_price'] * (100 - $new_order_item['discount_rate'])) * $new_order_item['amount'];
        $commission_agent_bonus = $new_order_item['commission_rate'] * $sellValue;
        $manager_bonus = ($sellValue - $commission_agent_bonus) * $new_order_item['manager_bonus_rate'];

        $this->update("UPDATE `order_items` SET manager_bonus = $manager_bonus,
                      commission_agent_bonus = $commission_agent_bonus
                      WHERE order_item_id = $order_item_id");

        $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${new_order_item['product_id']}");


//        $total_price = $new_order_item['purchase_price'] * $amount;
//        $reduced_price = (1.0 - $new_order_item['discount_rate'] / 100.0) * $total_price;
//        $manager_bonus = $new_order_item['manager_bonus_rate'] / 100.0 * $reduced_price;


//$this->update("UPDATE order_items
//            SET amount = $amount, number_of_packs = $number_of_packs, total_price = $total_price, reduced_price = $reduced_price, manager_bonus = $manager_bonus
//            WHERE order_item_id = $order_item_id");

//        $order_id = $new_order_item['order_id'];
//        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
//        $total_price = $order['total_price'] - $old_order_item['reduced_price'] + $reduced_price;
//        $manager_bonus = $order['manager_bonus'] - $old_order_item['manager_bonus'] + $manager_bonus;

        $this->updateOrderPrice($orderId);

        if ($field == 'item_status') {
            $this->updateItemsStatus($orderId);
        }
        return true;
    }


    public function updateItemsStatus($orderId)
    {
        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE order_id = $orderId");
        $status=10;
        if (!empty($orderItems))
            foreach ($orderItems as $orderItem) {
                $newStatus = array_search($orderItem['item_status'], $this->statuses);
                if ($newStatus < $status)
                    $status = $newStatus;
            }
        $orderStatus = isset($this->statuses[$status]) ? $this->statuses[$status] : '';
        $this->update("UPDATE `orders` 
                SET order_status = '$orderStatus' WHERE order_id = $orderId");
    }

    public function updateOrderPrice($orderId)
    {
        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE order_id = $orderId");
        $totalPrice = 0;
        $totalCommission = 0;
        $managerBonus = 0;
        if (!empty($orderItems)) {
            foreach ($orderItems as $orderItem) {
                $sellValue = ($orderItem['sell_price'] * (100 - $orderItem['discount_rate'])) * $orderItem['amount'];
                $totalPrice += $sellValue;
                $totalCommission += $orderItem['commission_agent_bonus'];
                $managerBonus += $orderItem['manager_bonus'];
            }
        }
//        $total_commission = $order['commission_rate'] / 100 * $order['total_price'];
//        $this->update("UPDATE orders
//                SET total_price = $total_price, manager_bonus = $manager_bonus, total_commission = $total_commission
//                WHERE order_id = $order_id");
        $this->update("UPDATE orders 
                SET total_price = $totalPrice, total_commission = $totalCommission,
                 manager_bonus = $managerBonus WHERE order_id = $orderId");
    }

    public function getStatusList()
    {
        $statusList = [];
        $statuses = $this->statuses;
        foreach ($statuses as $status) {
            $item = new stdClass();
            $item->value = $status;
            $item->text = $status;
            $statusList[] = $item;
        }
        return $statusList;
    }

    public function getClientsOfManager($managerId)
    {
        return $this->getAssoc("SELECT client_id, name FROM clients WHERE sales_manager_id = $managerId");
    }
    public function getCommissionAgentsOfManager($managerId)
    {
        return $this->getAssoc("SELECT client_id, name FROM clients 
              WHERE sales_manager_id = $managerId AND type = 'Commission Agent'");
    }

    public function getReserveInformation($item_id)
    {

        $order_item = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $item_id");
        $amount = intval($order_item['amount']);
        $productId = $order_item['product_id'];

        $tableData = [];

//        $row = ['ordered' => $amount];

        // supplier
        $items = $this->getAssoc("SELECT * FROM suppliers_orders_items 
                    WHERE (ISNULL(reserved_manager_id) AND ISNULL(reserved_item_id) AND product_id = $productId)");
        $available = 0;
        $row = [];
        foreach ($items as $item) {
            $available = $item['amount'];
            $id = $item['order_id'];
            $row[$item['order_item_id']] = [
                'ordered' => $amount,
                'status' => $item['item_status'],
                'available' => $available,
                'source' => 'Supplier Order (<a href="/suppliers_order?id=' . $id . '">#'.$id.'</a>)'
            ];
        }
        unset($items);
//        $tableData['supplier'] = array_merge($row, ['available' => $available, 'source' => 'Supplier Order']);
        $tableData['supplier'] = $row;

        // truck
        $items = $this->getAssoc("SELECT * FROM trucks_items 
                    WHERE (ISNULL(reserved_manager_id) AND ISNULL(reserved_item_id) AND product_id = $productId)");
        $available = 0;
        $row = [];
        foreach ($items as $item) {
            $available = $item['amount'];
            $truckId = $item['truck_id'];
            $truckItemId = $item['truck_item_id'];
            $row[$truckItemId] = [
                'ordered' => $amount,
                'status' => $this->getItemStatus('truck', $truckItemId),
                'available' => $available,
                'source' => 'Truck (<a href="/truck?id=' . $truckId . '">#'.$truckId.'</a>)'
            ];
        }
        unset($items);
        $tableData['truck'] = $row;
//        $tableData['truck'] = array_merge($row, ['available' => $available, 'source' => 'Truck']);

        // supplier
        $items = $this->getAssoc("SELECT * FROM products_warehouses 
                    WHERE (ISNULL(reserved_manager_id) AND ISNULL(reserved_item_id) AND product_id = $productId)");
        $available = 0;
        $row = [];
        foreach ($items as $item) {
            $available = $item['amount'];
            $warehouseItemId = $item['product_warehouse_id'];
            $warehouseId = $item['warehouse_id'];
            $row[$warehouseItemId] = [
                'ordered' => $amount,
                'status' => $this->getItemStatus('truck', $warehouseItemId),
                'available' => $available,
                'source' => 'Warehouse (<a href="/warehouse?id=' . $warehouseId . '">#'.$warehouseId.'</a>)'
            ];
        }
        $tableData['warehouse'] = $row;
//        $tableData['warehouse'] = array_merge($row, ['available' => $available, 'source' => 'Warehouse']);

        return json_encode($tableData);

    }

    public function reserve($itemId, $reserved_item_id, $type)
    {

        $table = '';
        $idName = '';
        switch ($type) {
            case 'supplier':
                $table = 'suppliers_orders_items';
                $idName = 'order_item_id';
                break;
            case 'truck':
                $table = 'trucks_items';
                $idName = 'truck_item_id';
                break;
            case 'warehouse':
                $table = 'products_warehouses';
                $idName = 'product_warehouse_id';
                break;
        }
        if ($table) {
            $currentOrderItem = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $itemId");
            $productId = $currentOrderItem['product_id'];
//            $items = $this->getAssoc("SELECT amount, `$idName` as id FROM $table
//                        WHERE (ISNULL(reserved_manager_id) AND ISNULL(reserved_item_id) AND product_id = $productId)");
            $reserved = $this->getFirst("SELECT * FROM $table WHERE $idName = $reserved_item_id");
            if ($reserved && !empty($reserved)) {

                $ordered = ($currentOrderItem['amount'] && $currentOrderItem['amount'] !== null) ?
                    floatval($currentOrderItem['amount']) : 0;

                $available = floatval($reserved['amount']);


                $order_id = $currentOrderItem['order_id'];
                $order = $this->getFirst("SELECT sales_manager_id FROM orders WHERE order_id = $order_id");
                $managerId = $order['sales_manager_id'];

                // ordered > available
                if ($ordered > $available) {

                    $insertNames = '';
                    $insertValues = '';
                    $updateSellPrice = 0;
                    foreach ($currentOrderItem as $name => $value) {
                        if ($name != 'order_item_id') {

                            if ($name == 'amount')
                                $value = $available;
                            if ($name == 'item_status') {
                                $value = (string) $this->getItemStatus($type, $reserved_item_id);
                            }
                            if ($name == 'sell_price') {
                                $updateSellPrice = floatval($value);
                            }

                            if (!$value || $value == null)
                                continue;
                            $insertNames .= $name . ', ';
                            if (is_numeric($value)) {
                                $value = floatval($value);
                                $insertValues .= "$value, ";
                            } else {
                                $insertValues .= "'$value', ";
                            }
                        }
                    }

                    $amount = $ordered - $available;
                    // Insert new reservation in Order
                    if ($insertNames && $insertValues) {
                        $insertNames = substr($insertNames, 0, -2);
                        $insertValues = substr($insertValues, 0, -2);
                        $newOrderItemId = $this->insert("INSERT INTO order_items ($insertNames)
                          VALUES ($insertValues)");
                    }

                    // recalc parameters for current and new items (replace by updating amount instead)
                    $this->updateItemField($newOrderItemId, 'sell_price', $updateSellPrice);
                    $this->updateItemField($itemId, 'sell_price', $updateSellPrice);

                    // Update reserved item in Source
                    $this->update("UPDATE $table SET reserved_item_id = $itemId,
                                    reserved_manager_id = $managerId WHERE $idName = $reserved_item_id");

                    $this->update("UPDATE order_items SET `amount` = $amount WHERE order_item_id = $itemId");

                } elseif ($ordered == $available) {

                    $this->update("UPDATE $table SET reserved_item_id = $itemId,
                                    reserved_manager_id = $managerId WHERE $idName = $reserved_item_id");
                    $status = $this->getItemStatus($type, $reserved_item_id);
                    $this->update("UPDATE order_items SET item_status = '$status' WHERE order_item_id = $itemId");

                } elseif ($ordered < $available) {

                    $newAmount = $available - $ordered;
                    $this->update("UPDATE $table SET amount = $newAmount WHERE $idName = $reserved_item_id");
                    $newStatus = $this->getItemStatus($type, $reserved_item_id);
                    $this->update("UPDATE order_items SET item_status = '$newStatus' WHERE order_item_id = $itemId");
                    $currentReservedSourceItem = $this->getFirst("SELECT * FROM $table WHERE $idName = $reserved_item_id");
                    $insertNames = '';
                    $insertValues = '';
                    foreach ($currentReservedSourceItem as $name => $value) {
                        if ($name != $idName) {

                            if ($name == 'amount')
                                $value = $ordered;
                            if ($name == 'reserved_manager_id')
                                $value = (string) $managerId;
                            if ($name == 'reserved_item_id')
                                $value = (string) $itemId;

                            if (!$value || $value == null)
                                continue;
                            $insertNames .= $name . ', ';
                            if (is_numeric($value)) {
                                $value = floatval($value);
                                $insertValues .= "$value, ";
                            } else {
                                $insertValues .= "'$value', ";
                            }
                        }
                    }
                    if ($insertNames && $insertValues) {
                        $insertNames = substr($insertNames, 0, -2);
                        $insertValues = substr($insertValues, 0, -2);
                        $newSourceItemId = $this->insert("INSERT INTO $table ($insertNames)
                          VALUES ($insertValues)");
                    }

                }
                // Update current item in Order
            }
//            if (!empty($items)) {
////                $ordered = ($currentOrderItem['amount'] && $currentOrderItem['amount'] !== null) ? $currentOrderItem['amount'] : 0;
///*                $availableItems = [];
//                $available = 0;
//                foreach ($items as $item) {
//                    if ($availableAmount = floatval($item['amount']) && $item['amount'] !== null) {
//                        $availableItems[] = ['id' => $item['id'], 'amount' => $availableAmount];
//                        $available += floatval($availableAmount);
//                    }
//                }
//
//                $insertNames = '';
//                $insertValues = '';
//                foreach ($currentOrderItem as $name => $value) {
//                    if ($name != 'order_item_id' && $name != 'is_reserve') {
//
//                        if ($name == 'amount')
//                            $value = $available;
//
//                        if (!$value || $value == null)
//                            continue;
//                        $insertNames .= $name . ', ';
//                        $insertValues .= "$value, ";
//                    }
//                }*/
//
//                /*// ordered > available
//                if ($ordered > $available) {
//                    $amount = $ordered - $available;
//                    $newOrderItemId = $this->insert("INSERT INTO order_items ($insertNames is_reserve)
//                          VALUES ($insertValues 1)");
//                    $set = "`amount` = $amount";
//                } elseif ($ordered == $available) {
//                    $set = "`is_reserve` = 1";
//                } elseif ($ordered < $available) {
//
//                }
//                $this->update("UPDATE order_items SET $set WHERE order_item_id = $itemId");*/
//
//
////                foreach ($availableItems as $availableItem) {
////                    $amount = $availableItem['amount'];
////                    if ($amount) {
////                        if ($available) {
////                            $newAmount = ($available >= $amount) ? 0 : $amount - $available;
////                            if ($available - $amount >= 0) {
////                                $this->update("UPDATE $table SET amount = $newAmount, reserved_item_id = $newOrderItemId");
////                            }
////                        } else {
////                            break;
////                        }
////                    }
////                }
//            }

        }
    }

    public function getItemStatus($type, $item_id)
    {
        $status = [];
        if ($type == 'supplier') {
            $status = $this->getFirst("SELECT item_status FROM suppliers_orders_items WHERE order_item_id = $item_id");
        } elseif ($type == 'truck') {
            $supplierItem = $this->getFirst("SELECT suppliers_order_item_id FROM trucks_items WHERE truck_item_id = $item_id");
            $sup_item_id = $supplierItem['suppliers_order_item_id'];
            $status = $this->getFirst("SELECT item_status FROM suppliers_orders_items WHERE order_item_id = $sup_item_id");
        } elseif ($type == 'warehouse') {
            $status['item_status'] = 'Not done yet'; // TODO fix it
        }

        if ($status && !empty($status)) {
            return $status['item_status'];
        }

    }
}