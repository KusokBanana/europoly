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
            array('dt' => 4, 'db' => "IFNULL(CAST(order_items.purchase_price as decimal(64, 2)), '')"),
            array('dt' => 5, 'db' => "IFNULL(CAST(order_items.purchase_price * order_items.amount as decimal(64, 2)), '')"),
            array('dt' => 6, 'db' => "IFNULL(CAST(order_items.sell_price as decimal(64, 2)), '')"),
            array('dt' => 7, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-discount_rate\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"discount_rate\" data-value=\"',
                IFNULL(order_items.discount_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Discount Rate, %\">',
                    IFNULL(CONCAT(order_items.discount_rate, '%'), ''),
                '</a>')"),
            array('dt' => 8, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"reduced-price\" data-value=\"',
                IFNULL(CAST(order_items.sell_price * (100 - order_items.discount_rate) as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Reduced Price\">',
                    IFNULL(CAST(order_items.sell_price * (100 - order_items.discount_rate) as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 9, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-sell-price\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"sell-value\" data-value=\"',
                IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount as decimal(64, 2)), ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Sell Value\">',
                    IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount as decimal(64, 2)), ''),
                '</a>')"),
            array('dt' => 10, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-manager_bonus_rate\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"manager_bonus_rate\" data-value=\"',
                IFNULL(order_items.manager_bonus_rate, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Enter Manager Bonus Rate, %\">',
                    IFNULL(CONCAT(order_items.manager_bonus_rate, '%'), ''),
                '</a>')"),
            array('dt' => 11, 'db' => "IFNULL(order_items.manager_bonus, '')"),
            array('dt' => 12, 'db' => "CONCAT(IF(products.width = NULL, 'Width undefined', 
                                        IF(products.length = NULL, 'Length undefined', 
                                            order_items.amount / (products.width * products.length))
                                            ), '')"),
            array('dt' => 13, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                order_items.order_item_id,
                '\" data-name=\"item_status\" data-value=\"',
                IFNULL(order_items.item_status, ''),
                '\" data-url=\"/order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    IFNULL(order_items.item_status, ''),
                '</a>')"),
            array('dt' => 14, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                    IF(order_items.item_status = 'Draft' OR order_items.item_status = 'Hold' OR order_items.item_status = 'Sent to Logist', 
                        CONCAT('<a href=\"/order/delete_order_item?order_id=', order_items.order_id, '&order_item_id=', order_items.order_item_id,
                        '\" onclick=\"return confirm(\'Are you sure to delete the item?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'), 
                        ''),
                    IF(order_items.item_status = 'Hold',
                        CONCAT('<a href=\"/order/send_to_logist?order_item_id=', order_items.order_item_id,
                        '\" onclick=\"return confirm(\'Are you sure to send to logist the item?\')\"><span class=\'glyphicon glyphicon-download-alt\' title=\'Send to Logist\'></span></a>'),
                        ''),
                    IF(order_items.item_status = 'Expects Issue',
                        CONCAT('<a href=\"/order/issue?order_id=', order_items.order_id, '&order_item_id=', order_items.order_item_id,
                        '\" onclick=\"return confirm(\'Are you sure to create issue for the item?\')\"><span class=\'fa fa-share\' title=\'Issue\'></span></a>'),
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
            $number_of_packs = $product['amount_in_pack'] != null ? 0 : 1;
            $productPrice = $product[$price_field_name] != null ? $product[$price_field_name] : 0;
            $totalPrice = $order['total_price'] + $productPrice;
            $this->insert("INSERT INTO order_items (order_id, product_id, purchase_price, amount, number_of_packs, total_price, discount_rate, reduced_price, manager_bonus_rate, manager_bonus, item_status, sell_price)
                VALUES ($order_id, $product_id, $productPrice, 1, $number_of_packs, 0, $discount_rate, 0, 
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

        if ($field == 'reduced-price' || $field == 'sell-value') {
            switch ($field) {
                case 'reduced-price':
                    $discountRate = 100 - $new_value / $old_order_item['sell_price'];
                    return $this->update("UPDATE `order_items` SET discount_rate = $discountRate 
                      WHERE order_item_id = $order_item_id");
                case 'sell-value':
                    $amount = ($old_order_item['amount'] ? $old_order_item['amount'] : 1);
                    $discountRate = 100 - $new_value / ($old_order_item['sell_price'] * $amount);
                    return $this->update("UPDATE `order_items` SET discount_rate = $discountRate 
                      WHERE order_item_id = $order_item_id");
            }
            return false;
        }

        $result = $this->update("UPDATE `order_items` SET `$field` = '$new_value' WHERE order_item_id = $order_item_id");
        $new_order_item = $this->getFirst("SELECT * FROM order_items WHERE order_item_id = $order_item_id");

        $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${new_order_item['product_id']}");

        if ($product['amount_in_pack'] != null) {
            if ($old_order_item['amount'] != $new_order_item['amount']) {
                $number_of_packs = ($new_order_item['amount'] / $product['amount_in_pack']);
//                $number_of_packs = ceil($new_order_item['amount'] / $product['amount_in_pack']);
            } else {
                $number_of_packs = $new_order_item['number_of_packs'];
            }
            $amount = $number_of_packs * $product['amount_in_pack'];
        } else {
            $number_of_packs = 1;
            $amount = $new_order_item['amount'];
        }
        $total_price = $new_order_item['purchase_price'] * $amount;
        $reduced_price = (1.0 - $new_order_item['discount_rate'] / 100.0) * $total_price;
        $manager_bonus = $new_order_item['manager_bonus_rate'] / 100.0 * $reduced_price;
        $this->update("UPDATE order_items 
            SET amount = $amount, number_of_packs = $number_of_packs, total_price = $total_price, reduced_price = $reduced_price, manager_bonus = $manager_bonus
            WHERE order_item_id = $order_item_id");

        $order_id = $new_order_item['order_id'];
        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $order_id");
        $total_price = $order['total_price'] - $old_order_item['reduced_price'] + $reduced_price;
        $manager_bonus = $order['manager_bonus'] - $old_order_item['manager_bonus'] + $manager_bonus;
        $total_commission = $order['commission_rate'] / 100 * $order['total_price'];
        $this->update("UPDATE orders 
                SET total_price = $total_price, manager_bonus = $manager_bonus, total_commission = $total_commission
                WHERE order_id = $order_id");

        if ($field == 'item_status') {
            $this->updateItemsStatus($order_id);
        }

        return $result;
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
}