<?php

class ModelSuppliers_order extends Model
{

    public function __construct()
    {
        $this->connect_db();
    }


    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "suppliers_orders_items.order_item_id"),
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
                suppliers_orders_items.order_item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(suppliers_orders_items.amount, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(suppliers_orders_items.amount, ' ', products.units), ''),
                '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                suppliers_orders_items.order_item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(suppliers_orders_items.number_of_packs, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(suppliers_orders_items.number_of_packs, ''),
                '</a>')"),
        array('dt' => 4, 'db' => "IFNULL(CAST(products.purchase_price as decimal(64, 2)), '')"),
        array('dt' => 5, 'db' => "IFNULL(CAST(products.purchase_price * suppliers_orders_items.number_of_packs as decimal(64, 2)), '')"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                suppliers_orders_items.order_item_id,
                '\" data-name=\"item_status\" data-value=\"',
                IFNULL(order_items.item_status, suppliers_orders_items.item_status),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    IFNULL(order_items.item_status, suppliers_orders_items.item_status),
                '</a>')"),
        array('dt' => 7, 'db' => "products.weight"),
        array('dt' => 8, 'db' => "orders.downpayment_rate"),
        array('dt' => 9, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 10, 'db' => "managers.first_name"),
        array('dt' => 11, 'db' => "CONCAT('<a href=\"/order?id=',
                order_items.order_id,
                '\">', order_items.order_id, '</a>')"),
        array('dt' => 12, 'db' => "clients.name"),
        array('dt' => 13, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/suppliers_order/delete_order_item?order_id=', suppliers_orders_items.order_id, '&order_item_id=', suppliers_orders_items.order_item_id,
                        '\" onclick=\"return confirm(\'Are you sure to delete the item?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
];

    function getDTOrderItems($order_id, $input)
    {
        $table = 'suppliers_orders_items
    left join products on suppliers_orders_items.product_id = products.product_id
    left join brands on products.brand_id = brands.brand_id
    left join colors on products.color_id = colors.color_id
    left join colors as colors2 on products.color2_id = colors2.color_id
    left join constructions on products.construction_id = constructions.construction_id
    left join wood on products.wood_id = wood.wood_id
    left join grading on products.grading_id = grading.grading_id
    left join patterns on products.pattern_id = patterns.pattern_id
    left join suppliers_orders on suppliers_orders_items.order_id = suppliers_orders.order_id
    left join suppliers on suppliers_orders.supplier_id = suppliers.supplier_id
    left join order_items on (order_items.order_item_id = suppliers_orders_items.managers_order_item_id)
    left join orders on order_items.order_id = orders.order_id
    left join clients on orders.client_id = clients.client_id
    left join users as managers on orders.sales_manager_id = managers.user_id';


        $this->sspComplex($table, "DISTINCT suppliers_orders_items.order_item_id", $this->suppliers_orders_columns,
            $input, null, "suppliers_orders_items.order_id = $order_id");
    }

    public function getOrder($order_id)
    {
        return $this->getById('suppliers_orders', 'order_id', $order_id);
    }

    public function getOrderStatus($order_id)
    {
        $products = $this->getFirst("SELECT status FROM suppliers_orders WHERE order_id = $order_id");
        return isset($products['status']) ? $products['status'] : '';
    }

    function changeStatus($order_id, $status)
    {
        $this->update("UPDATE suppliers_orders SET status = '$status' WHERE order_id = $order_id");
    }

    function cancelOrder($order_id, $cancel_reason)
    {
        $this->update("UPDATE suppliers_orders SET order_status = 'Cancelled', cancel_reason = '$cancel_reason' WHERE order_id = $order_id");
    }

    function addOrderItem($order_id, $product_ids)
    {
        foreach ($product_ids as $product_id) {
            $product = $this->getFirst("SELECT * FROM products WHERE product_id = $product_id");
            $existProduct = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE (product_id = $product_id AND 
              managers_order_item_id = 0)");
            if ($existProduct && !empty($existProduct)) {
                $this->update("UPDATE suppliers_orders_items SET amount = ${existProduct['amount']} + 1 
                  WHERE order_item_id = ${existProduct['order_item_id']}");
            }
            else {
                $this->insert("INSERT INTO suppliers_orders_items (order_id, product_id, total_price, amount, 
                  number_of_packs, item_status)
                VALUES ($order_id, $product_id, 0, 0, 0, 'Draft for Supplier')");
            }

            $order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
            $order_items_count = $order['order_items_count'] + 1;
            $this->update("UPDATE suppliers_orders 
                SET order_items_count = $order_items_count
                WHERE order_id = $order_id");
        }
        $this->updateItemsStatus($order_id);
    }

    function deleteOrderItem($order_id, $order_item_id)
    {
        $order_item = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_item_id = $order_item_id");
        if ($managerOrderItem = $order_item['managers_order_item_id']) {
            $this->update("UPDATE order_items SET item_status = 'Sent to Supplier' WHERE order_item_id = $managerOrderItem");
        }
        $this->delete("DELETE FROM suppliers_orders_items WHERE order_item_id = $order_item_id");

        $this->updateItemsStatus($order_id);

        $order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
        $order_items_count = $order['order_items_count'] - $order_item['amount'];

        $this->update("UPDATE suppliers_orders 
                SET order_items_count = $order_items_count 
                WHERE order_id = $order_id");

    }

    public function updateField($order_id, $field, $new_value)
    {
        $old_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
        $result = $this->update("UPDATE `suppliers_orders` SET `$field` = '$new_value' WHERE order_id = $order_id");
        $new_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");

        $total_price = $old_order['total_price'] - $old_order['special_expenses'] + $new_order['special_expenses'];
        $total_commission = $new_order['commission_rate'] * $total_price / 100;
        $this->update("UPDATE suppliers_orders 
                SET total_price = $total_price, total_commission = $total_commission
                WHERE order_id = $order_id");

        return $result;
    }

    public function updateItemField($order_item_id, $field, $new_value)
    {
        $old_order_item = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_item_id = $order_item_id");
        if ($old_order_item['managers_order_item_id'] && $field == 'item_status') {
            $this->update("UPDATE `order_items` SET item_status = '$new_value' WHERE 
              order_item_id = ${old_order_item['managers_order_item_id']}");
        }
        else
            $result = $this->update("UPDATE `suppliers_orders_items` SET `$field` = '$new_value' 
              WHERE order_item_id = $order_item_id");
        $new_order_item = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_item_id = $order_item_id");

        //        Изменим статус самого объекта
        if ($field == 'item_status') {
            $orderId = $old_order_item['order_id'];
            $this->updateItemsStatus($orderId);
        }

        $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${new_order_item['product_id']}");
        if ($product['amount_in_pack'] != null) {
            if ($old_order_item['amount'] != $new_order_item['amount']) {
                $number_of_packs = ceil($new_order_item['amount'] / $product['amount_in_pack']);
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
        $this->update("UPDATE suppliers_orders_items 
            SET amount = $amount, number_of_packs = $number_of_packs, total_price = $total_price, reduced_price = $reduced_price, manager_bonus = $manager_bonus
            WHERE order_item_id = $order_item_id");

        $order_id = $new_order_item['order_id'];
        $order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
        $total_price = $order['total_price'] - $old_order_item['reduced_price'] + $reduced_price;
        $manager_bonus = $order['manager_bonus'] - $old_order_item['manager_bonus'] + $manager_bonus;
        $total_commission = $order['commission_rate'] / 100 * $order['total_price'];
        $this->update("UPDATE suppliers_orders 
                SET total_price = $total_price, manager_bonus = $manager_bonus, total_commission = $total_commission
                WHERE order_id = $order_id");

        return $result;
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

    public function getStatusList()
    {
        $statusList = [];
        $statuses = array_slice($this->statuses, 2);
        foreach ($statuses as $status) {
            $item = new stdClass();
            $item->value = $status;
            $item->text = $status;
            $statusList[] = $item;
        }
        return $statusList;
    }

    public function getSupplier($supplier_id)
    {
        $suppliersList = [];
        $suppliers = $this->getAll("suppliers");
        $currentSupplierName = '';
        foreach ($suppliers as $supplier) {
            $item = new stdClass();
            if ($supplier_id == $supplier['supplier_id'])
                $currentSupplierName = $supplier['name'];
            $item->value = $supplier['supplier_id'];
            $item->text = $supplier['name'];
            $suppliersList[] = $item;
        }
        return ['list' => $suppliersList, 'name' => $currentSupplierName];
    }

}