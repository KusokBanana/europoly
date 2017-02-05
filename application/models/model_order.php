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
            array('dt' => 0, 'db' => "order_items.item_id"),
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
                order_items.item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(order_items.amount, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(order_items.amount, ' ', products.units), ''),
                '</a>')"),
            array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                order_items.item_id,
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
                                            ), 'n/a'), '')"),
            array('dt' => 5, 'db' => "IFNULL(CAST(order_items.purchase_price as decimal(64, 2)), '')"),
            array('dt' => 6, 'db' => "IFNULL(CAST(order_items.purchase_price * order_items.amount as decimal(64, 2)), '')"),
            array('dt' => 7, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"sell_price\" data-value=\"',
                IFNULL(CAST(order_items.sell_price as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Sell Price, %\">',
                    IFNULL(CAST(order_items.sell_price as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 8, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-discount_rate\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"discount_rate\" data-value=\"',
                IFNULL(order_items.discount_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Discount Rate, %\">',
                    IFNULL(CONCAT(order_items.discount_rate, '%'), ''),
                '</a>')"),
            array('dt' => 9, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"reduced-price\" data-value=\"',
                IFNULL(CAST(order_items.sell_price * (100 - order_items.discount_rate)/100 as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Reduced Price\">',
                    IFNULL(CAST(order_items.sell_price * (100 - order_items.discount_rate)/100 as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 10, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"sell-value\" data-value=\"',
                IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount/100 as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Sell Value\">',
                    IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount/100 as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 11, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_rate\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"commission_rate\" data-value=\"',
                IFNULL(order_items.commission_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Commission Rate\">',
                    IFNULL((CONCAT(order_items.commission_rate, '%')), ''),
                '</a>')"),
            array('dt' => 12, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_agent_bonus\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"commission_agent_bonus\" data-value=\"',
                IFNULL(order_items.commission_agent_bonus, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Commission Agent Bonus\">',
                    IFNULL(order_items.commission_agent_bonus, ''),
                '</a>')"),
            array('dt' => 13, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus_rate\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"manager_bonus_rate\" data-value=\"',
                IFNULL(order_items.manager_bonus_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus Rate, %\">',
                    IFNULL(CONCAT(order_items.manager_bonus_rate, '%'), ''),
                '</a>')"),
            array('dt' => 14, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"manager_bonus\" data-value=\"',
                IFNULL(order_items.manager_bonus, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus\">',
                    IFNULL(order_items.manager_bonus, ''),
                '</a>')"),
            array('dt' => 15, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"status_id\" data-value=\"',
                IFNULL(order_items.status_id, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    IFNULL(status.name, ''),
                '</a>')"),
            array('dt' => 16, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                    IF(order_items.status_id = 1,
                    CONCAT('<a href=\"/order/hold?order_item_id=', order_items.item_id,
                            '\" onclick=\"return confirm(\'Are you sure to hold the item?\')\">
                                <span class=\'glyphicon glyphicon-star\' title=\'Hold Item\'></span>
                            </a>'), 
                    ''),
                    IF(order_items.status_id = 1 OR order_items.status_id = 2, 
                        CONCAT('<a href=\"/order/reserve?order_item_id=', order_items.item_id, '&action=get_info',
                                '\" class=\"reserve-product-btn\" data-id=\"', order_items.item_id, '\">
                                    <span class=\'glyphicon glyphicon-heart\' title=\'Reserve Item\'></span>
                                </a>',
                                '<a href=\"/order/send_to_logist?order_item_id=', order_items.item_id,
                                '\" onclick=\"return confirm(\'Are you sure to send to logist the item?\')\">
                                    <span class=\'glyphicon glyphicon-download-alt\' title=\'Send to Logist\'></span>
                                </a>',
                                '<a href=\"/order/delete_order_item?order_id=', order_items.manager_order_id, 
                                '&order_item_id=', order_items.item_id,
                                '\" onclick=\"return confirm(\'Are you sure to delete the item?\')\">
                                    <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                </a>'), 
                        ''),
                    IF(order_items.status_id = 8,
                        CONCAT('<a href=\"/order/issue?order_item_id=', order_items.item_id,
                                '\" onclick=\"return confirm(\'Are you sure to create issue for the item?\')\">
                                    <span class=\'fa fa-share\' title=\'Issue\'></span>
                                </a>'),
                        ''),
                '</div>')"),
        ];

//        $table = $this->full_products_table . ' join order_items on products.product_id = order_items.product_id';

        $table = 'order_items 
                    left join products on order_items.product_id = products.product_id ' .
                    $this->full_products_table_addition .
                    ' left join items_status as status on order_items.status_id = status.status_id';

        $where = "order_items.manager_order_id = $order_id AND order_items.reserve_since_date IS NULL";
        return $this->sspComplex($table, "order_items.item_id", $columns, $input, null, $where);
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

            $productPrice = $product[$price_field_name] != null ? $product[$price_field_name] : 0;
            $totalPrice = $order['total_price'] + $productPrice;
            $this->insert("INSERT INTO order_items (manager_order_id, product_id, purchase_price, amount, number_of_packs, total_price, discount_rate, reduced_price, manager_bonus_rate, manager_bonus, sell_price)
                VALUES ($order_id, $product_id, $productPrice, 0, 0, 0, $discount_rate, 0, 
                $manager_bonus_rate, 0, ${product['sell_price']})");
            $order_items_count = $order['order_items_count'] + 1;
            $this->update("UPDATE orders 
                SET order_items_count = $order_items_count, total_price = $totalPrice
                WHERE order_id = $order_id");
        }
        $this->updateItemsStatus($order_id);
    }

    function deleteOrderItem($order_id, $order_item_id)
    {
        $order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $order_item_id");
        $this->delete("DELETE FROM order_items WHERE item_id = $order_item_id");

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
        $old_order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $order_item_id");
        $orderId = $old_order_item['manager_order_id'];

        if ($field == 'reduced-price' || $field == 'sell-value') {
            switch ($field) {
                case 'reduced-price':
                    $discountRate = 100 - $new_value / $old_order_item['sell_price'] * 100;
                    break;
                case 'sell-value':
                    $amount = ($old_order_item['amount'] ? $old_order_item['amount'] : 1);
                    $discountRate = 100 - $new_value / ($old_order_item['sell_price'] * $amount) * 100;
                    break;
            }
            $sellValue = ($old_order_item['sell_price'] * (100 - $discountRate)) * $old_order_item['amount'] / 100;
            $commission_agent_bonus = $old_order_item['commission_rate'] * $sellValue / 100;
            $manager_bonus = ($sellValue - $commission_agent_bonus) * $old_order_item['manager_bonus_rate'] / 100;

            $this->update("UPDATE `order_items` SET discount_rate = $discountRate, 
                commission_agent_bonus = $commission_agent_bonus, manager_bonus = $manager_bonus
                      WHERE item_id = $order_item_id");
            $this->updateOrderPrice($orderId);
            return true;
        }
        if ($field == 'commission_agent_bonus' || $field == 'commission_rate') {
            switch ($field) {
                case 'commission_agent_bonus':
                    // Update Sell Price
                    $sellPrice = $new_value / ($old_order_item['amount'] * $old_order_item['commission_rate'] * (100 - $old_order_item['discount_rate'])) * 100 * 100;
                    $this->update("UPDATE `order_items` SET sell_price = $sellPrice
                          WHERE item_id = $order_item_id");
                    break;
                case 'commission_rate':
                    $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) *
                        $old_order_item['amount'] / 100;
                    $commission_agent_bonus = $sellValue * $new_value / 100;
                    $this->update("UPDATE `order_items` SET commission_agent_bonus = $commission_agent_bonus,
                      commission_rate = $new_value WHERE item_id = $order_item_id");
                    $this->updateOrderPrice($orderId);
                    return true;
            }
        }
        if ($field == 'manager_bonus' || $field == 'manager_bonus_rate') {
            $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) * $old_order_item['amount'] / 100;
            switch ($field) {
                case 'manager_bonus':
                    $manager_bonus_rate = $new_value / ($sellValue - $old_order_item['commission_agent_bonus']) * 100;
                    $this->update("UPDATE `order_items` SET manager_bonus = $new_value, manager_bonus_rate = $manager_bonus_rate
                      WHERE item_id = $order_item_id");
                    $this->updateOrderPrice($orderId);
                    return true;
                case 'manager_bonus_rate':
                    $manager_bonus = ($sellValue*100 - $old_order_item['commission_agent_bonus']) * $new_value / 100;
                    $this->update("UPDATE `order_items` SET manager_bonus = $manager_bonus 
                      WHERE item_id = $order_item_id");
                    break;
            }
        }

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
                                    WHERE item_id = $order_item_id");
            $this->updateOrderPrice($orderId);
            return true;
        }

        $this->update("UPDATE `order_items` SET `$field` = '$new_value' WHERE item_id = $order_item_id");

        $new_order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $order_item_id");

        $sellValue = ($new_order_item['sell_price'] * (100 - $new_order_item['discount_rate'])) * $new_order_item['amount'] / 100;
        $commission_agent_bonus = $new_order_item['commission_rate'] * $sellValue / 100;
        $manager_bonus = ($sellValue - $commission_agent_bonus) * $new_order_item['manager_bonus_rate'] / 100;

        $this->update("UPDATE `order_items` SET manager_bonus = $manager_bonus,
                      commission_agent_bonus = $commission_agent_bonus
                      WHERE item_id = $order_item_id");



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

        if ($field == 'status_id') {
            $this->updateItemsStatus($orderId);
        }
        return true;
    }


    public function updateItemsStatus($orderId)
    {
        $status = $this->getFirst("SELECT status_id FROM order_items WHERE manager_order_id = $orderId AND 
                                    status_id = (SELECT MIN(status_id) FROM order_items)");
        $orderStatus = $status ? $status['status_id'] : 1;
        $this->update("UPDATE `orders` 
                SET order_status_id = $orderStatus WHERE order_id = $orderId");
    }

    public function updateOrderPrice($orderId)
    {
        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE manager_order_id = $orderId");
        $totalPrice = 0;
        $totalCommission = 0;
        $managerBonus = 0;
        if (!empty($orderItems)) {
            foreach ($orderItems as $orderItem) {
                $sellValue = ($orderItem['sell_price'] * (100 - $orderItem['discount_rate'])) * $orderItem['amount'] / 100;
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
        return $this->getAssoc("SELECT status_id as value, name as text FROM items_status");
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

        $order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $item_id");
        $amount = intval($order_item['amount']);
        $productId = $order_item['product_id'];

        $tableData = [];
        $sourceItems = $this->getAssoc("SELECT * FROM order_items 
                    WHERE (ISNULL(manager_order_id) AND ISNULL(reserve_since_date) AND product_id = $productId)");
        if (!empty($sourceItems)) {
            foreach ($sourceItems as $sourceItem) {

                $sourceParams = [];
                if ($id = $sourceItem['warehouse_id']) {
                    $sourceParams = ['warehouse', 'Warehouse', $id];
                } elseif ($id = $sourceItem['truck_id']) {
                    $sourceParams = ['truck', 'Truck', $id];
                } elseif ($id = $sourceItem['supplier_order_id']) {
                    $sourceParams = ['suppliers_order', 'Supplier Order', $id];
                }
                if (!empty($sourceParams)) {

                    $available = $sourceItem['amount'];
                    $item_id = $sourceItem['item_id'];
                    $tableData[$sourceParams[0]][$item_id] = [
                        'ordered' => $amount,
                        'status' => $this->getItemStatusName($sourceItem['status_id']),
                        'available' => $available,
                        'source' => "$sourceParams[1] (<a href=\"$sourceParams[0]?id=$sourceParams[2]\">#$sourceParams[2]</a>)"
                    ];

                }
            }
        }
        return !empty($tableData) ? json_encode($tableData) : false;
    }

    public function reserve($itemId, $reserved_item_id, $type)
    {
        $currentOrderItem = $this->getFirst("SELECT * FROM order_items WHERE item_id = $itemId");
        $reserved = $this->getFirst("SELECT * FROM order_items WHERE item_id = $reserved_item_id");
        if ($reserved && !empty($reserved)) {

            $ordered = ($currentOrderItem['amount'] && $currentOrderItem['amount'] !== null) ?
                floatval($currentOrderItem['amount']) : 0;

            $available = floatval($reserved['amount']);

            $order_id = $currentOrderItem['manager_order_id'];
            $order = $this->getFirst("SELECT sales_manager_id FROM orders WHERE order_id = $order_id");

            // ordered > available
            if ($ordered > $available) {

                $insertNames = '';
                $insertValues = '';
                $updateSellPrice = 0;
                foreach ($currentOrderItem as $name => $value) {
                    if ($name != 'item_id') {

                        if ($name == 'amount')
                            $value = $available;
                        if ($name == 'status_id') {
                            $value = $reserved['status_id'];
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

                    // recalc parameters for current and new items (replace by updating amount instead)
                    $this->updateItemField($itemId, 'sell_price', $updateSellPrice);
                    $this->updateItemField($newOrderItemId, 'sell_price', $updateSellPrice);
                }

                // Update reserved item in Source
                $this->update("UPDATE order_items SET reserve_since_date = NOW(), reserve_till_date = ADDDATE(NOW(), 7),
                        manager_order_id = $order_id WHERE item_id = $reserved_item_id");
                // update current item
                $this->update("UPDATE order_items SET `amount` = $amount WHERE item_id = $itemId");

            } elseif ($ordered == $available) {

                $this->update("UPDATE order_items SET reserve_since_date = NOW(), manager_order_id = $order_id,
                                reserve_till_date = ADDDATE(NOW(), 7) 
                                WHERE item_id = $reserved_item_id");
                $this->update("UPDATE order_items SET status_id = ${reserved['status_id']} WHERE item_id = $itemId");

            } elseif ($ordered < $available) {

                $newAmount = $available - $ordered;
                $this->update("UPDATE order_items SET amount = $newAmount WHERE item_id = $reserved_item_id");
                $this->update("UPDATE order_items SET status_id = ${reserved['status_id']} WHERE item_id = $itemId");
                $currentReservedSourceItem = $this->getFirst("SELECT * FROM order_items WHERE item_id = $reserved_item_id");
                $insertNames = '';
                $insertValues = '';
                foreach ($currentReservedSourceItem as $name => $value) {
                    if ($name != 'item_id') {

                        if ($name == 'amount')
                            $value = $ordered;

                        if ($name == 'manager_order_id')
                            $value = (string) $order_id;

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
                    $newSourceItemId = $this->insert("INSERT INTO order_items ($insertNames reserve_since_date, reserve_till_date)
                      VALUES ($insertValues NOW(), ADDDATE(NOW(), 7))");
                }

            }
                // Update current item in Order
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

    public function getItemStatusName($status_id)
    {
        $status = $this->getFirst("SELECT name FROM items_status WHERE status_id = $status_id");
        return $status ? $status['name'] : 'WTF?!?';

    }
}