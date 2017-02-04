<?php
include_once 'model_order.php';

class ModelSuppliers_order extends ModelOrder
{
    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "suppliers_orders_items.item_id"),
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
                suppliers_orders_items.item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(suppliers_orders_items.amount, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(suppliers_orders_items.amount, ' ', products.units), ''),
                '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(suppliers_orders_items.number_of_packs, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(suppliers_orders_items.number_of_packs, ''),
                '</a>')"),
        array('dt' => 4, 'db' => "IFNULL(CAST(suppliers_orders_items.purchase_price as decimal(64, 2)), '')"),
        array('dt' => 5, 'db' => "IFNULL(CAST(suppliers_orders_items.purchase_price * suppliers_orders_items.number_of_packs as decimal(64, 2)), '')"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"status_id\" data-value=\"',
                suppliers_orders_items.status_id,
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    status.name,
                '</a>')"),
        array('dt' => 7, 'db' => "products.weight"),
        array('dt' => 8, 'db' => "orders.downpayment_rate"),
        array('dt' => 9, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 10, 'db' => "managers.first_name"),
        array('dt' => 11, 'db' => "CONCAT('<a href=\"/order?id=',
                suppliers_orders_items.manager_order_id,
                '\">', suppliers_orders_items.manager_order_id, '</a>')"),
        array('dt' => 12, 'db' => "clients.name"),
        array('dt' => 13, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/suppliers_order/delete_order_item?order_id=', suppliers_orders_items.supplier_order_id, '&order_item_id=', suppliers_orders_items.item_id,
                        '\" onclick=\"return confirm(\'Are you sure to delete the item?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
];

    function getDTOrderItems($order_id, $input)
    {
        $table = 'order_items as suppliers_orders_items
                left join products on suppliers_orders_items.product_id = products.product_id ' .
                $this->full_products_table_addition . ' 
                left join suppliers_orders on suppliers_orders_items.supplier_order_id = suppliers_orders.order_id
                left join suppliers as order_supplier on suppliers_orders.supplier_id = order_supplier.supplier_id
                left join orders on suppliers_orders_items.manager_order_id = orders.order_id
                left join items_status as status on suppliers_orders_items.status_id = status.status_id
                left join clients on orders.client_id = clients.client_id
                left join users as managers on orders.sales_manager_id = managers.user_id';


        $this->sspComplex($table, "suppliers_orders_items.item_id", $this->suppliers_orders_columns,
            $input, null, "suppliers_orders_items.supplier_order_id = $order_id");
    }

    public function getOrder($order_id)
    {
        return $this->getById('suppliers_orders', 'order_id', $order_id);
    }

    public function getOrderStatus($order_id)
    {
        $status = $this->getFirst("SELECT status.name as statusName
          FROM suppliers_orders as s 
          LEFT JOIN items_status as status ON s.status_id = status.status_id 
          WHERE s.order_id = $order_id");
        return $status ? $status['statusName'] : '';
    }

    function changeStatus($order_id, $status)
    {
        $this->update("UPDATE suppliers_orders SET status_id = $status WHERE order_id = $order_id");
    }

    function addOrderItem($order_id, $product_ids)
    {
        $count = 0;
        foreach ($product_ids as $product_id) {
            $this->insert("INSERT INTO order_items (supplier_order_id, product_id, total_price, amount, 
              number_of_packs, status_id)
            VALUES ($order_id, $product_id, 0, 0, 0, 4)");
            $count++;
        }
        $this->update("UPDATE suppliers_orders 
                      SET order_items_count = order_items_count + $count
                      WHERE order_id = $order_id");
        $this->updateItemsStatus($order_id);
    }

    function deleteOrderItem($order_id, $order_item_id)
    {
//        $order_item = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_item_id = $order_item_id");
//        if ($managerOrderItem = $order_item['managers_order_item_id']) {
//            $this->update("UPDATE order_items SET item_status = 'Sent to Supplier' WHERE order_item_id = $managerOrderItem");
//        }
//        $this->delete("DELETE FROM suppliers_orders_items WHERE order_item_id = $order_item_id");
        $this->update("UPDATE order_items SET status_id = 1, supplier_order_id = NULL,
                       WHERE item_id = $order_item_id");

        $this->updateItemsStatus($order_id);

        $this->update("UPDATE suppliers_orders 
                SET order_items_count = order_items_count - 1 
                WHERE order_id = $order_id");
    }

    public function updateField($order_id, $field, $new_value)
    {
        $old_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
        $result = $this->update("UPDATE `suppliers_orders` SET `$field` = '$new_value' WHERE order_id = $order_id");
        $new_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");

        $total_price = $old_order['total_price'] - $old_order['special_expenses'] + $new_order['special_expenses'];
        $this->update("UPDATE suppliers_orders 
                SET total_price = $total_price
                WHERE order_id = $order_id");

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
            $this->update("UPDATE order_items SET amount = $amount, number_of_packs = $number_of_packs 
                           WHERE item_id = $order_item_id");
            return true;
        }


        $this->update("UPDATE order_items SET `$field` = $new_value WHERE item_id = $order_item_id");

//        Изменим статус самого объекта
        if ($field == 'status_id') {
            $orderId = $old_order_item['supplier_order_id'];
            $this->updateItemsStatus($orderId);
        }

        return true;
    }

    public function updateItemsStatus($orderId)
    {
        $status = $this->getFirst("SELECT status_id FROM order_items WHERE  
                                    status_id = (SELECT MIN(status_id) FROM order_items WHERE supplier_order_id = $orderId)");
        $orderStatus = $status ? $status['status_id'] : 4;
        $this->update("UPDATE `suppliers_orders` 
                SET status_id = $orderStatus WHERE order_id = $orderId");
    }

    public function getStatusList()
    {
        $statusList = parent::getStatusList();
        return array_slice($statusList, 2);
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