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
        array('dt' => 2, 'db' => "IF(suppliers_orders_items.manager_order_id IS NOT NULL, 
            CONCAT(suppliers_orders_items.amount, ' ', products.units),
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-amount\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(suppliers_orders_items.amount, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(suppliers_orders_items.amount, ' ', products.units), ''),
                '</a>'))"),
        array('dt' => 3, 'db' => "IF(suppliers_orders_items.manager_order_id IS NOT NULL,
            CONCAT(suppliers_orders_items.number_of_packs, ' ', IFNULL(products.packing_type, '')),
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(suppliers_orders_items.number_of_packs, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(CONCAT(suppliers_orders_items.number_of_packs, ' ', IFNULL(products.packing_type, '')), ''),
                '</a>'))"),
        array('dt' => 4, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-purchase_price\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"purchase_price\" data-value=\"',
                IFNULL(CAST(suppliers_orders_items.purchase_price as decimal(64, 2)), ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Purchase Price\">',
                    IFNULL(CAST(suppliers_orders_items.purchase_price as decimal(64, 2)), ''),
                '</a>')"),
        array('dt' => 5, 'db' => "IFNULL(CAST(suppliers_orders_items.purchase_price * suppliers_orders_items.amount as decimal(64, 2)), '')"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"status_id\" data-value=\"',
                suppliers_orders_items.status_id,
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    status.name,
                '</a>')"),
        array('dt' => 7, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-production_date\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"production_date\" data-value=\"', IFNULL(suppliers_orders_items.production_date, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Choose Production Date\">',
                    IFNULL(suppliers_orders_items.production_date, ''),
                '</a>')"),
        array('dt' => 8, 'db' => "CAST(products.weight * suppliers_orders_items.amount as decimal(64, 3))"),
        array('dt' => 9, 'db' => "CONCAT(orders.downpayment_rate, ' %')"),
        array('dt' => 10, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 11, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 12, 'db' => "CONCAT('<a href=\"/order?id=',
                suppliers_orders_items.manager_order_id,
                '\">', suppliers_orders_items.manager_order_id,
                 IF(suppliers_orders_items.reserve_since_date IS NULL, '', (CONCAT(' (reserved ', suppliers_orders_items.reserve_since_date, ')'))), '</a>')"),
        array('dt' => 13, 'db' => "clients.name"),
        array('dt' => 14, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the item?\" 
                        href=\"/suppliers_order/delete_order_item?order_id=', suppliers_orders_items.supplier_order_id,
                            '&order_item_id=', suppliers_orders_items.item_id, '\"
                        class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" data-singleton=\"true\">
                            <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                        </a>'),
                        IF(suppliers_orders_items.reserve_since_date IS NOT NULL,
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete from reserve the item?\"  
                        href=\"/suppliers_order/delete_from_reserve?order_item_id=', suppliers_orders_items.item_id, '\"
                        class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" data-singleton=\"true\">
                            <span class=\'glyphicon glyphicon-remove\' title=\'Delete from reserve\'></span>
                        </a>'), ''),
                '</div>')")
];

    var $suppliers_orders_column_names = [
        'ID',
        'Product',
        'Quantity',
        '# of packs',
        'Purchase Price',
        'Purchase Value',
        'Status',
        'Production Date',
        'Weight',
        'Downpayment rate, %',
        'Client\'s expected date of issue',
        'Manager',
        'Managers order id ',
        'Client',
        'Actions',
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

        $roles = new Roles();
        $columns = $roles->returnModelColumns($this->suppliers_orders_columns, 'suppliersOrder');
        if ($_SESSION['perm'] <= ADMIN_PERM) {
            $this->unLinkStrings($columns, [6]);
        }
        $this->sspComplex($table, "suppliers_orders_items.item_id", $columns,
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
            $product = $this->getFirst("SELECT purchase_price FROM products WHERE product_id = $product_id");
            $purchase_price = ($product && $product['purchase_price']) ? $product['purchase_price'] : 0;
            $this->insert("INSERT INTO order_items (supplier_order_id, product_id, amount, 
              number_of_packs, status_id, purchase_price)
            VALUES ($order_id, $product_id, 0, 0, ".DRAFT_FOR_SUPPLIER.", $purchase_price)");
            $count++;
        }
        $this->update("UPDATE suppliers_orders 
                      SET order_items_count = order_items_count + $count
                      WHERE order_id = $order_id");
        $this->updateItemsStatus($order_id);
    }

    function deleteOrderItem($order_id, $order_item_id)
    {

        $this->update("UPDATE order_items SET status_id = ".DRAFT.", supplier_order_id = NULL
                       WHERE item_id = $order_item_id");

        $this->updateItemsStatus($order_id);

        $this->update("UPDATE suppliers_orders 
                SET order_items_count = order_items_count - 1 
                WHERE order_id = $order_id");
    }

    public function updateField($order_id, $field, $new_value)
    {
//        $old_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
//        $result = $this->update("UPDATE `suppliers_orders` SET `$field` = '$new_value' WHERE order_id = $order_id");
//        $new_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
//
//        $this->update("UPDATE suppliers_orders
//                SET total_price = $total_price
//                WHERE order_id = $order_id");
//
//        return $result;
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

        $this->update("UPDATE order_items SET `$field` = '$new_value' WHERE item_id = $order_item_id");

        if ($field == 'production_date') {
            $this->updateOrderProductionDate($old_order_item['supplier_order_id']);
        }

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
        $orderStatus = $status ? $status['status_id'] : DRAFT_FOR_SUPPLIER;
        $this->update("UPDATE `suppliers_orders` 
                SET status_id = $orderStatus WHERE order_id = $orderId");
    }

    public function updateOrderProductionDate($orderId)
    {
        $item = $this->getFirst("SELECT MAX(production_date) as production_date FROM order_items WHERE supplier_order_id = $orderId");
        if ($item) {
            $productionDate = $item['production_date'];
            $this->update("UPDATE suppliers_orders SET production_date = '$productionDate' WHERE order_id = $orderId");
        }
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

    public function deleteFromReserve($order_item_id)
    {

        $item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $order_item_id");
        $result = $this->update("UPDATE order_items SET manager_order_id = NULL, reserve_since_date = NULL, reserve_till_date = NULL
          WHERE item_id = $order_item_id");
        if ($result) {
            return $this->update("UPDATE order_items SET status_id = ".DRAFT." WHERE (manager_order_id = ${item['manager_order_id']} AND
          product_id = ${item['product_id']} AND status_id > ".DRAFT.")");
        }
    }

    public function printDoc($orderId, $type = '')
    {

        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE supplier_order_id = $orderId");
        $fileName = 'supplier';

        if (!empty($orderItems)) {
            $array = $this->getProductsDataArrayForDocPrint($orderItems);

            $products = $array['products'];
            $values = $array['values'];

            require dirname(__FILE__) . "/../../assets/PHPWord_CloneRow-master/PHPWord.php";
            $phpWord =  new PHPWord();
            $docFile = dirname(__FILE__) . "/../../docs/templates/$fileName.docx";

            $order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $orderId");
            $values['order_id'] = $orderId;
            $values['date'] = date('Y-m-d', strtotime($order['supplier_date_of_order']));

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
                'href' => "/suppliers_order/print?order_id=$orderId",
                'name' => 'Print'
            ],
        ];
        return $docs;
    }

}