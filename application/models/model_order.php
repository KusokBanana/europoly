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
                '\">', IFNULL(products.visual_name, 'Enter Visual Name!'),
                '</a>')"),
            array('dt' => 2, 'db' => "IF(order_items.status_id > ".HOLD.", 
                CONCAT(order_items.amount, ' ', IFNULL(products.units, '')),
                CONCAT('<a href=\"javascript:;\" class=\"x-editable x-amount\" data-pk=\"',
                    order_items.item_id,
                    '\" data-name=\"amount\" data-value=\"',
                    IFNULL(order_items.amount, ''),
                    '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Quantity\">',
                        IFNULL(CONCAT(order_items.amount, ' ', products.units), ''),
                    '</a>'))"),
            array('dt' => 3, 'db' => "IF(order_items.status_id > ".HOLD.", 
            CONCAT(order_items.number_of_packs, ' ', IFNULL(products.packing_type, '')),
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(order_items.number_of_packs, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(CONCAT(order_items.number_of_packs, ' ', products.packing_type), ''),
                '</a>'))"),
            array('dt' => 4, 'db' => "CONCAT(IF(products.units = 'm2' AND products.length NOT LIKE '%-%' 
                                                                        AND products.width NOT LIKE '%-%',
                                        IF(products.width = NULL, 'Width undefined', 
                                        IF(products.length = NULL, 'Length undefined', 
                                            CAST((order_items.amount * 1000 * 1000) / (products.width * products.length) as decimal(64, 2)))
                                            ), 'n/a'), '')"),
            array('dt' => 5, 'db' => "IFNULL(CAST(order_items.purchase_price as decimal(64, 2)), '')"),
            array('dt' => 6, 'db' => "IFNULL(CAST(order_items.purchase_price * order_items.amount as decimal(64, 2)), '')"),
            array('dt' => 7, 'db' => "IFNULL(CAST(order_items.sell_price as decimal(64, 2)), '')"),
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
                    IFNULL((CONCAT(CAST(order_items.commission_rate as decimal(64, 2)), '%')), ''),
                '</a>')"),
            array('dt' => 12, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-commission_agent_bonus\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"commission_agent_bonus\" data-value=\"',
                IFNULL(order_items.commission_agent_bonus, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Commission Agent Bonus\">',
                    IFNULL(CAST(order_items.commission_agent_bonus as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 13, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus_rate\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"manager_bonus_rate\" data-value=\"',
                IFNULL(order_items.manager_bonus_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus Rate, %\">',
                    IFNULL(CONCAT(CAST(order_items.manager_bonus_rate as decimal(64, 2)), '%'), ''),
                '</a>')"),
            array('dt' => 14, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"manager_bonus\" data-value=\"',
                IFNULL(order_items.manager_bonus, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus\">',
                    IFNULL(CAST(order_items.manager_bonus as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 15, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                order_items.item_id,
                '\" data-name=\"status_id\" data-value=\"',
                IFNULL(order_items.status_id, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    IFNULL(status.name, ''),
                '</a>')"),
            array('dt' => 16, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                    IF(order_items.status_id = ". DRAFT .",
                    CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to hold the item?\" 
                               href=\"/order/hold?order_item_id=', order_items.item_id, '\" 
                               class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" data-singleton=\"true\">
                                <span class=\'glyphicon glyphicon-star\' title=\'Hold Item\'></span>
                            </a>'), 
                    ''),
                    IF(order_items.status_id = ".DRAFT." OR order_items.status_id = ".HOLD.", 
                        CONCAT('<a href=\"/order/reserve?order_item_id=', order_items.item_id, '&action=get_info',
                                '\" class=\"reserve-product-btn\" data-id=\"', order_items.item_id, '\">
                                    <span class=\'glyphicon glyphicon-heart\' title=\'Reserve Item\'></span>
                                </a>',
                                '<a data-toggle=\"confirmation\" data-title=\"Are you sure to send to logist the item?\" 
                                    href=\"/order/send_to_logist?order_item_id=', order_items.item_id, '\"
                                    class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-download-alt\' title=\'Send to Logist\'></span>
                                </a>',
                                '<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the item?\" 
                                    href=\"/order/delete_order_item?order_id=', order_items.manager_order_id, 
                                    '&order_item_id=', order_items.item_id, '\" 
                                    class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                    data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                </a>'), 
                        ''),
                    IF(order_items.status_id = ".ON_STOCK.",
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to create issue for the item?\" 
                                   href=\"/order/issue?order_item_id=', order_items.item_id, '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                    <span class=\'fa fa-share\' title=\'Issue\'></span>
                                </a>'),
                        ''),
                    IF(order_items.status_id = ".ISSUED.",
                        CONCAT('<a class=\"return-item-btn\" data-item_id=\"', order_items.item_id, '\">
                                    <span class=\'glyphicon glyphicon-repeat\' title=\'Return\'></span>
                                </a>'),
                        ''),
                    IF(order_items.status_id >= ".SENT_TO_LOSIGT." AND " . intval(($this->user->role_id == ROLE_ADMIN)) .",
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to return this item to manager?\" 
                                   href=\"/order/hold?order_item_id=', order_items.item_id, '\" 
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                    <span class=\'glyphicon glyphicon-pencil\' title=\'Return to Manager\'></span>
                                </a>'),
                        ''),
                '</div>')"),
        ];

//        $table = $this->full_products_table . ' join order_items on products.product_id = order_items.product_id';

        $table = 'order_items 
                    left join products on order_items.product_id = products.product_id ' .
                    $this->full_products_table_addition .
                    ' left join items_status as status on order_items.status_id = status.status_id';

        $where = ["order_items.manager_order_id = $order_id", 'order_items.is_deleted = 0'];

        $roles = new Roles();

        if ($_SESSION['perm'] < ADMIN_PERM) {
            $this->unLinkStrings($columns, [13, 14, 15]);
        }
        if ($_SESSION['perm'] >= OPERATING_MANAGER_PERM) {
            $this->unLinkStrings($columns, [15]);
        }

        $columns = $roles->returnModelColumns($columns, 'order');

        return $this->sspComplex($table, "order_items.item_id", $columns, $input, null, $where);
    }

    var $order_columns_names = [
        'Id',
        'Product',
        'Quantity',
        '# of Packs',
        '# of planks',
        'Purchase Price',
        'Purchase Value',
        'Sell Price',
        'Discount Rate (%)',
        'Reduced Price',
        'Sell Value',
        'Commission Rate (%)',
        'Commission Agent Bonus',
        'Manager Bonus Rate (%)',
        'Manager Bonus',
        'Status',
        'Actions'
    ];

//    function cancelOrder($order_id, $cancel_reason)
//    {
//        $this->update("UPDATE orders SET order_status_id = 'Cancelled', cancel_reason = '$cancel_reason' WHERE order_id = $order_id");
//    }

    function deleteCommissionAgent($order_id)
    {
        $this->update("UPDATE orders SET commission_agent_id = NULL, commission_rate = 0, total_commission = 0, 
          commission_status = null WHERE order_id = $order_id");
    }

    function addOrderItem($order_id, $product_ids)
    {
        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
        $client = $this->getFirst("SELECT * FROM clients WHERE client_id = ${order['client_id']}");
        $sales_manager = $this->getFirst("SELECT * FROM users WHERE user_id = ${order['sales_manager_id']}");
        $manager_bonus_rate = isset($sales_manager['manager_bonus_rate']) && $sales_manager['manager_bonus_rate'] != "" ? $sales_manager['manager_bonus_rate'] : 0;
        $discount_rate = $client['type'] == 'Dealer' ? 30 : 0;
        foreach ($product_ids as $product_id) {
            $product = $this->getFirst("SELECT * FROM products WHERE product_id = $product_id");

            $productPrice = $product['purchase_price'] != null ? $product['purchase_price'] : 0;
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
        $this->update("UPDATE order_items SET is_deleted = 1 WHERE item_id = $order_item_id");
//        $this->delete("DELETE FROM order_items WHERE item_id = $order_item_id");

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
        if ($field == 'start_date') {
            $new_value = date('Y-m-d H:i:s', strtotime($new_value));
            $this->update("UPDATE `orders` SET `start_date` = '$new_value' WHERE order_id = $order_id");
            return true;
        }
        $result = $this->update("UPDATE `orders` SET `$field` = '$new_value' WHERE order_id = $order_id");
        $new_order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");

        if ($field == 'legal_entity_id') {
            $currentYear = date('Y');
            $currentYear = substr($currentYear, -2, 2);
            $legal = $this->getFirst("SELECT prefix FROM legal_entities WHERE legal_entity_id = $new_value");
            if ($legal) {
                $prefix = $legal['prefix'];
                $likeStr = "^$prefix"."$currentYear";
                $visibleIds = $this->getAssoc("SELECT visible_order_id FROM orders 
                  WHERE (legal_entity_id = $new_value && visible_order_id RLIKE '$likeStr')");
                if ($visibleIds) {
                    $finalIds = [];
                    foreach ($visibleIds as $visibleId) {
                        $array = explode("$currentYear/", $visibleId['visible_order_id']);
                        $finalIds[] = intval($array[1]);
                    }
                    $maxLegalId = !empty($finalIds) ? max($finalIds) + 1 : 1;
                } else {
                    $maxLegalId = 1;
                }
                $newVisibleId = $prefix . $currentYear . '/' . str_pad($maxLegalId, 5, '0', STR_PAD_LEFT);
                $this->update("UPDATE orders SET visible_order_id = '$newVisibleId' WHERE order_id = $order_id");
            }
        }

//        $total_price = $old_order['total_price'] - $old_order['special_expenses'] + $new_order['special_expenses'];
//        $total_commission = $new_order['commission_rate'] * $total_price / 100;
//        $this->update("UPDATE orders
//                SET total_price = $total_price, total_commission = $total_commission
//                WHERE order_id = $order_id");

        return $result;
    }

    public function updateItemField($order_item_id, $field, $new_value)
    {
        $old_order_item = $this->getFirst("SELECT * FROM order_items 
          WHERE item_id = $order_item_id AND is_deleted = 0");
        $orderId = $old_order_item['manager_order_id'];

        $reserved = $this->getFirst("SELECT item_id FROM order_items 
          WHERE reserved_for_order_item_id = $order_item_id AND is_deleted = 0");
        if ($reserved)
            $this->updateItemField($reserved['item_id'], $field, $new_value);

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
                    $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) *
                        $old_order_item['amount']/100;
                    $commission_rate = $new_value / $sellValue * 100;
                    $manager_bonus = ($sellValue - $new_value) * $old_order_item['manager_bonus_rate'] / 100;
                    $this->update("UPDATE `order_items` SET commission_rate = $commission_rate, commission_agent_bonus = $new_value,
                          manager_bonus = $manager_bonus WHERE item_id = $order_item_id");
                    $this->updateOrderPrice($orderId);
                    return true;
                case 'commission_rate':
                    $sellValue = ($old_order_item['sell_price'] * (100 - $old_order_item['discount_rate'])) *
                        $old_order_item['amount'] / 100;
                    $commission_agent_bonus = $sellValue * $new_value / 100;
                    $manager_bonus = ($sellValue - $commission_agent_bonus) * $old_order_item['manager_bonus_rate'] / 100;
                    $this->update("UPDATE `order_items` SET commission_agent_bonus = $commission_agent_bonus,
                      commission_rate = $new_value, manager_bonus = $manager_bonus WHERE item_id = $order_item_id");
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
        $this->clearCache(['managers_orders_selects', 'sent_to_logist']);
        return true;
    }


    public function updateItemsStatus($orderId)
    {
        $status = $this->getFirst("SELECT status_id FROM order_items WHERE manager_order_id = $orderId AND 
                                    status_id = (SELECT MIN(status_id) FROM order_items) AND is_deleted = 0");
        $orderStatus = $status ? $status['status_id'] : DRAFT;
        $this->update("UPDATE `orders` 
                SET order_status_id = $orderStatus WHERE order_id = $orderId");
        $this->clearCache(['managers_orders_selects', 'sent_to_logist']);
    }

    public function updateOrderPrice($orderId)
    {
        if (!$orderId || is_null($orderId))
            return false;

        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE manager_order_id = $orderId AND is_deleted = 0");
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
        return $this->getAssoc("SELECT client_id, final_name as name FROM clients WHERE sales_manager_id = $managerId");
    }

    public function getCommissionAgentsOfManager($managerId)
    {
        return $this->getAssoc("SELECT client_id, final_name as name FROM clients 
              WHERE sales_manager_id = $managerId AND type = 'Comission Agent'"); // TODO REPLACE_CONST
    }

    public function getReserveInformation($item_id)
    {

        $order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $item_id");
        $amount = floatval($order_item['amount']);
        $productId = $order_item['product_id'];

        $tableData = [];
        $sourceItems = $this->getAssoc("SELECT * FROM order_items 
                    LEFT JOIN products ON (products.product_id = order_items.product_id)
                    WHERE (ISNULL(order_items.manager_order_id) AND ISNULL(order_items.reserve_since_date) 
                    AND order_items.product_id = $productId)");
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
                        'product' => $sourceItem['name'],
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
        $currentProductItem = $this->getFirst("SELECT * FROM products WHERE product_id = ${currentOrderItem['product_id']}");
        $reserved = $this->getFirst("SELECT * FROM order_items WHERE item_id = $reserved_item_id");
        if ($reserved && !empty($reserved)) {

            $ordered = ($currentOrderItem['amount'] && $currentOrderItem['amount'] !== null) ?
                floatval($currentOrderItem['amount']) : 0;

            $available = floatval($reserved['amount']);
            $status_id = $reserved['status_id'];

            $order_id = $currentOrderItem['manager_order_id'];

            if (!$available || !$ordered)
                return false;

            // ordered > available
            if ($ordered > $available) {

                $amount = $ordered - $available;

                // Update reserved item in Source
                $commission_rate = floatval($currentOrderItem['commission_rate']);
                $discount_rate = floatval($currentOrderItem['discount_rate']);
                $manager_bonus_rate = floatval($currentOrderItem['manager_bonus_rate']);
                $sell_price = floatval($currentOrderItem['sell_price']);
                $issue_date = (!is_null($reserved['issue_date'])) ?
                    $reserved['issue_date'] : null;
                $return_date = (!is_null($reserved['return_date'])) ?
                    $reserved['return_date'] : null;

                $this->update("UPDATE order_items SET reserve_since_date = NOW(), reserve_till_date = ADDDATE(NOW(), 7),
                        manager_order_id = $order_id, 
                        commission_rate = $commission_rate,
                        discount_rate = $discount_rate,
                        manager_bonus_rate = $manager_bonus_rate,
                        sell_price = $sell_price,
                        issue_date = '$issue_date',
                        return_date = '$return_date'
                        WHERE item_id = $reserved_item_id");

                $this->updateItemField($itemId, 'amount', $amount);
                $this->updateItemField($reserved_item_id, 'sell_price', $currentOrderItem['sell_price']);

            } elseif ($ordered == $available) {

                $this->update("UPDATE order_items SET 
                                is_deleted = 1
                                WHERE item_id = $reserved_item_id");

                $purchasePrice = floatval($reserved['purchase_price']);
                $supOrderId = (!is_null($reserved['supplier_order_id'])) ? $reserved['supplier_order_id'] : 'NULL';
                $truck_id = (!is_null($reserved['truck_id'])) ? $reserved['truck_id'] : 'NULL';
                $warehouse_arrival_date = (!is_null($reserved['warehouse_arrival_date'])) ?
                    $reserved['warehouse_arrival_date'] : 'NULL';
                $import_tax = floatval($reserved['import_tax']);
                $delivery_price = floatval($reserved['delivery_price']);
                $import_VAT = floatval($reserved['import_VAT']);
                $import_brokers_price = floatval($reserved['import_brokers_price']);
                $warehouse_id = $reserved['warehouse_id'];
                $buy_and_taxes = floatval($reserved['buy_and_taxes']);
                $production_date = (!is_null($reserved['production_date'])) ?
                    $reserved['production_date'] : 'NULL';

                $this->update("UPDATE order_items SET 
                    status_id = $status_id, 
                    reserve_till_date = ADDDATE(NOW(), 7),
                    reserve_since_date = NOW(),
                    purchase_price = $purchasePrice,
                    supplier_order_id = $supOrderId,
                    truck_id = $truck_id,
                    warehouse_arrival_date = '$warehouse_arrival_date',
                    import_tax = $import_tax,
                    delivery_price = $delivery_price,
                    import_VAT = $import_VAT,
                    import_brokers_price = $import_brokers_price,
                    warehouse_id = $warehouse_id,
                    buy_and_taxes = $buy_and_taxes,
                    production_date = '$production_date'
                    WHERE item_id = $itemId");

            } elseif ($ordered < $available) {

                $newAmount = $available - $ordered;

                $purchasePrice = floatval($reserved['purchase_price']);
                $supOrderId = (!is_null($reserved['supplier_order_id'])) ? $reserved['supplier_order_id'] : 'NULL';
                $truck_id = (!is_null($reserved['truck_id'])) ? $reserved['truck_id'] : 'NULL';
                $warehouse_arrival_date = (!is_null($reserved['warehouse_arrival_date'])) ?
                    $reserved['warehouse_arrival_date'] : 'NULL';
                $import_tax = floatval($reserved['import_tax']);
                $delivery_price = floatval($reserved['delivery_price']);
                $import_VAT = floatval($reserved['import_VAT']);
                $import_brokers_price = floatval($reserved['import_brokers_price']);
                $warehouse_id = $reserved['warehouse_id'];
                $buy_and_taxes = floatval($reserved['buy_and_taxes']);
                $production_date = (!is_null($reserved['production_date'])) ?
                    $reserved['production_date'] : 'NULL';
                $this->update("UPDATE order_items 
                    SET status_id = $status_id, 
                    reserve_since_date = NOW(), 
                    reserve_till_date = ADDDATE(NOW(), 7),
                    purchase_price = $purchasePrice,
                    supplier_order_id = $supOrderId,
                    truck_id = $truck_id,
                    warehouse_arrival_date = '$warehouse_arrival_date',
                    import_tax = $import_tax,
                    delivery_price = $delivery_price,
                    import_VAT = $import_VAT,
                    import_brokers_price = $import_brokers_price,
                    warehouse_id = $warehouse_id,
                    buy_and_taxes = $buy_and_taxes,
                    production_date = '$production_date'
                    WHERE item_id = $itemId");

                $this->updateItemField($reserved_item_id, 'amount', $newAmount);
                $this->updateItemField($itemId, 'amount', $ordered);

            }
        }
    }

    public function getItemStatusName($status_id)
    {
        $status = $this->getFirst("SELECT name FROM items_status WHERE status_id = $status_id");
        return $status ? $status['name'] : 'WTF?!?';

    }
    public function getLegalEntities()
    {
        return $this->getAssoc("SELECT legal_entity_id as value, name as text FROM legal_entities");
    }
    public function getLegalEntityName($id)
    {
        if ($id == null)
            return '';
        $name = $this->getFirst("SELECT name FROM legal_entities WHERE legal_entity_id = $id");
        return isset($name['name']) ? $name['name'] : '';
    }

    public function validateItemField($itemId, $name, $value)
    {

        $item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $itemId");
        switch ($name) {
            case 'reduced-price':
                $maxPrice = $item['sell_price'] + $item['sell_price'] * MANAGER_MAX_REDUCED_PRICE_INPUT / 100;
                if ($maxPrice >= $value)
                    return true;
                break;
            case 'sell-value':
                $sellValue = ($item['sell_price'] *
                        (100 - $item['discount_rate'])) * $item['amount']/100;
                $maxPrice = $sellValue + $sellValue * MANAGER_MAX_SELL_VALUE_INPUT / 100;
                if ($maxPrice >= $value)
                    return true;
                 break;
            case 'commission_agent_bonus':
                $sellValue = ($item['sell_price'] *
                        (100 - $item['discount_rate'])) * $item['amount']/100;
                $maxPrice = $sellValue * MANAGER_MAX_COMMISSION_AGENT_BONUS_INPUT / 100;
                if ($maxPrice >= $value)
                    return true;
                break;
        }
        return false;
    }

//    public function changePrice() {
//
//        $orderitems = $this->getAssoc("SELECT * FROM order_items WHERE sell_price = 0");
//        foreach ($orderitems as $orderitem) {
//            $prod = $this->getFirst("SELECT * FROM products WHERE product_id = ${orderitem['product_id']}");
//            if ($prod) {
//                $sellPrice = doubleval($prod['sell_price']);
//                if (!$sellPrice)
//                    continue;
//                $item = $orderitem['item_id'];
//                $this->update("UPDATE order_items SET sell_price = '$sellPrice' WHERE item_id = $item");
//            }
//        }
//
//    }

    public function printDoc($orderId, $type, $items = false)
    {
        $where = "manager_order_id = $orderId AND is_deleted = 0";
        if ($items) {
            $where .= " AND item_id IN ($items)";
        }

        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE $where");
        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $orderId");

//        $order = $this->getFirst("SELECT *
//                                            FROM orders
//                                            LEFT JOIN clients ON (clients.client_id = orders.client_id)
//                                            LEFT JOIN legal_entities ON (orders.legal_entity_id = legal_entities.legal_entity_id)

        switch ($type) {
            case 'payment':
                $fileName = 'payment';
                break;
            case 'order':
                $fileName = 'order';
                break;
            case 'return':
                $fileName = 'return';
                break;
        }

        if (!empty($orderItems)) {

            $multi = ($type == 'return') ? true : false;

            $additions = [
                'visible_order_id' => $order['visible_order_id']
            ];

            $array = $this->getProductsDataArrayForDocPrint($orderItems, $multi, $additions);

            $products = $array['products'];
            $values = $array['values'];

            require dirname(__FILE__) . "/../../assets/PHPWord_CloneRow-master/PHPWord.php";
            $phpWord =  new PHPWord();
            $docFile = dirname(__FILE__) . "/../../docs/templates/$fileName.docx";

            $values['order_id'] = $orderId;
            $values['date'] = date('Y-m-d', strtotime($order['start_date']));

            $values['current_date'] = date('d-m-Y');
            $client = $this->getFirst("SELECT * FROM clients WHERE client_id = ${order['client_id']}");
            if ($client) {
                $add[] = $client['name'];
                if (!is_null($client['inn']))
                    $add[] = 'ИНН ' . $client['inn'];
                if (!is_null($client['legal_address']))
                    $add[] = $client['legal_address'];
                $values['client'] = join(', ', $add);
            }

            $legalEntity = $this->getFirst("SELECT * FROM legal_entities 
                  WHERE legal_entity_id = ${order['legal_entity_id']}");
            $values['visual_legal_entity_name'] = $legalEntity['visual_name'];

            $user = $this->getFirst("SELECT * FROM users WHERE user_id = ".$_SESSION['user_id']);
            $values['manager'] = $user ? $user['last_name'] . ' ' . $user['first_name'] : '';
            $values['visible_order_id'] = $order['visible_order_id'];

            $templateProcessor = $phpWord->loadTemplate($docFile);

            $templateProcessor->cloneRow('TBL', $products);
            foreach ($values as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            $templateProcessor->save(dirname(__FILE__) . "/../../docs/ready/$fileName.docx");

            return "/docs/ready/$fileName.docx";
        }
    }

    public function getDocuments($orderId)
    {
        $docs = [
            [
                'href' => "/order/print_payment?order_id=$orderId&type=payment",
                'name' => 'Check for payment'
            ],
            [
                'href' => "/order/print_payment?order_id=$orderId&type=order",
                'name' => 'Buyer\'s order'
            ],
            [
                'href' => "/order/print_payment?order_id=$orderId&type=return",
                'name' => 'Return of goods'
            ],
        ];
        return $docs;
    }

    function getSelects()
    {
        $role = new Roles();
        $cols = $role->returnModelColumns($this->full_product_columns, 'catalogue');
        $ssp = $this->getSspComplexJson($this->full_products_table, "product_id", $cols, null);
        $columns = $role->returnModelNames($this->full_product_column_names, 'catalogue');
        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['_product_id', 'Name', 'Article', 'Thickness', 'Width', 'Length',
            'Weight', 'Quantity in 1 Pack', 'Purchase price', 'Supplier\'s discount',
            'Margin', 'Sell'];

        if (!empty($rowValues)) {
            $selects = [];
            foreach ($rowValues as $product) {
                foreach ($product as $key => $value) {
                    if (!$value || $value == null)
                        continue;
                    $name = $columns[$key];
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
            return ['selects' => $selects, 'rows' => $rowValues];
        }
    }

}