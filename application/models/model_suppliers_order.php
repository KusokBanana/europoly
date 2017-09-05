<?php
include_once 'model_order.php';

class ModelSuppliers_order extends ModelOrder
{
    var $suppliers_orders_columns = [
        array('dt' => 0, 'db' => "suppliers_orders_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
            CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the item?\" 
            href=\"/suppliers_order/delete_order_item?order_id=', suppliers_orders_items.supplier_order_id,
                '&order_item_id=', suppliers_orders_items.item_id, '\"
            class=\"table-confirm-btn\" data-placement=\"right\" data-popout=\"true\" data-singleton=\"true\">
                <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
            </a>'),
            IF(suppliers_orders_items.reserve_since_date IS NOT NULL,
            CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete from reserve the item?\"  
            href=\"/suppliers_order/delete_from_reserve?order_item_id=', suppliers_orders_items.item_id, '\"
            class=\"table-confirm-btn\" data-placement=\"right\" data-popout=\"true\" data-singleton=\"true\">
                <span class=\'glyphicon glyphicon-repeat\' title=\'Delete from reserve\'></span>
            </a>'), ''),
        '</div>')"),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/product?id=',
                products.product_id, '\">', IFNULL(products.visual_name, 'Enter Visual Name!'), '</a>')"),
        array('dt' => 3, 'db' => "IF(suppliers_orders_items.manager_order_id IS NOT NULL, 
            CONCAT(CAST(suppliers_orders_items.amount as decimal(64,3)), ' ', IFNULL(products.units, ' ')),
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-amount\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(suppliers_orders_items.amount, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(CAST(suppliers_orders_items.amount as decimal(64,3)), ' ', products.units), ''),
                '</a>'))"),
        array('dt' => 4, 'db' => "IF(suppliers_orders_items.manager_order_id IS NOT NULL,
            CONCAT(CAST(suppliers_orders_items.number_of_packs as decimal(64,3)), ' ', IFNULL(products.packing_type, '')),
            CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(suppliers_orders_items.number_of_packs, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(CONCAT(CAST(suppliers_orders_items.number_of_packs as decimal(64, 3)), ' ', 
                    IFNULL(products.packing_type, '')), ''),
                '</a>'))"),
        array('dt' => 5, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-purchase_price\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"purchase_price\" data-value=\"',
                IFNULL(CAST(suppliers_orders_items.purchase_price as decimal(64, 2)), ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Enter Purchase Price\">',
                    IFNULL(CAST(suppliers_orders_items.purchase_price as decimal(64, 2)), ''),
                '</a>')"),
        array('dt' => 6, 'db' => "IFNULL(CAST(suppliers_orders_items.purchase_price * suppliers_orders_items.amount as decimal(64, 2)), '')"),
        array('dt' => 7, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"status_id\" data-value=\"',
                suppliers_orders_items.status_id,
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Choose Item Status\">',
                    status.name,
                '</a>')"),
        array('dt' => 8, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-production_date\" data-pk=\"',
                suppliers_orders_items.item_id,
                '\" data-name=\"production_date\" data-value=\"', IFNULL(suppliers_orders_items.production_date, ''),
                '\" data-url=\"/suppliers_order/change_item_field\" data-original-title=\"Choose Production Date\">',
                    IFNULL(suppliers_orders_items.production_date, ''),
                '</a>')"),
        array('dt' => 9, 'db' => "CAST(products.weight * suppliers_orders_items.amount as decimal(64, 3))"),
        array('dt' => 10, 'db' => "CONCAT(orders.downpayment_rate, ' %')"),
        array('dt' => 11, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 12, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
        array('dt' => 13, 'db' => "CONCAT('<a href=\"/order?id=',
                suppliers_orders_items.manager_order_id,
                '\">', suppliers_orders_items.manager_order_id,
                 IF(suppliers_orders_items.reserve_since_date IS NULL, '', (CONCAT(' (reserved ', suppliers_orders_items.reserve_since_date, ')'))), '</a>')"),
        array('dt' => 14, 'db' => "clients.final_name"),
    ];

    var $suppliers_orders_column_names = [
        'ID',
        'Actions',
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
    ];

    function getDTOrderItems($order_id, $input, $printOpt)
    {
    	$ssp = $this->getSSPData('general', ['order_id' => $order_id]);

	    if ($printOpt) {
		    echo $this->printTable($input, $ssp, $printOpt);
		    return true;
	    }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'],
            $input, null, $ssp['where']);
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
            $id = $this->insert("INSERT INTO order_items (supplier_order_id, product_id, amount, 
              number_of_packs, status_id, purchase_price)
            VALUES ($order_id, $product_id, 0, 0, ".DRAFT_FOR_SUPPLIER.", $purchase_price)");
            $count++;
	        $this->updateItemsStatus($id);
        }
        $this->update("UPDATE suppliers_orders 
                      SET order_items_count = order_items_count + $count
                      WHERE order_id = $order_id");
    }

    function deleteOrderItem($order_id, $order_item_id)
    {

        $this->update("UPDATE order_items SET status_id = ".DRAFT.", supplier_order_id = NULL
                       WHERE item_id = $order_item_id");

        $this->updateItemsStatus($order_item_id);

        $this->update("UPDATE suppliers_orders 
                SET order_items_count = order_items_count - 1 
                WHERE order_id = $order_id");
    }

    public function updateField($order_id, $field, $new_value)
    {
//        $old_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
        $result = $this->update("UPDATE `suppliers_orders` SET `$field` = '$new_value' WHERE order_id = $order_id");
//        $new_order = $this->getFirst("SELECT * FROM suppliers_orders WHERE order_id = $order_id");
//
//        $this->update("UPDATE suppliers_orders
//                SET total_price = $total_price
//                WHERE order_id = $order_id");
//
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

        $this->update("UPDATE order_items SET `$field` = '$new_value' WHERE item_id = $order_item_id");

        if ($field == 'production_date') {
            $this->updateOrderProductionDate($old_order_item['supplier_order_id']);
        }

//        Изменим статус самого объекта
        if ($field == 'status_id') {
            $this->updateItemsStatus($order_item_id, $old_order_item);
        }

        return true;
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
        return array_slice($statusList, 3, 3);
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
            $result = $this->update("UPDATE order_items SET status_id = ".DRAFT." WHERE (manager_order_id = ${item['manager_order_id']} AND
          product_id = ${item['product_id']} AND status_id > ".DRAFT.")");
            $this->updateItemsStatus($item['item_id'], $order_item_id);
            return $result;
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

    function getSums($supplier_order_id)
    {
        $truckItems = $this->getAssoc("SELECT product_id, number_of_packs, sell_price, amount, purchase_price, amount
                        FROM order_items WHERE supplier_order_id = $supplier_order_id");

        return parent::getSums($truckItems);
    }

    function getSSPData($type = 'general', $opts = [])
    {
        $ssp = ['page' => $this->page];

        switch ($type) {
            case 'general':
	            $table = 'order_items as suppliers_orders_items
                left join products on suppliers_orders_items.product_id = products.product_id ' .
	                     $this->full_products_table_addition . ' 
                left join suppliers_orders on suppliers_orders_items.supplier_order_id = suppliers_orders.order_id
                left join suppliers as order_supplier on suppliers_orders.supplier_id = order_supplier.supplier_id
                left join orders on suppliers_orders_items.manager_order_id = orders.order_id
                left join items_status as status on suppliers_orders_items.status_id = status.status_id
                left join clients on orders.client_id = clients.client_id
                left join users as managers on orders.sales_manager_id = managers.user_id';

	            $columns = $this->suppliers_orders_columns;
	            if ($this->user->permissions <= ADMIN_PERM) {
		            $this->unLinkStrings($columns, [5]);
	            }

	            $tableName = 'table_suppliers_order_items';
	            $order_id = $opts['order_id'];
	            $ssp = array_merge($ssp, $this->getColumns($columns, $this->page, $tableName));
	            $ssp = array_merge($ssp, $this->getColumns($this->suppliers_orders_column_names, $this->page,
		            $tableName, true));
	            $ssp['db_table'] = $table;
	            $ssp['table_name'] = $tableName;
	            $ssp['primary'] = 'suppliers_orders_items.item_id';
	            $ssp['where'] = "suppliers_orders_items.supplier_order_id = $order_id";

            	break;
	        case 'orders_payments':
		        require_once 'model_accountant.php';
		        $model = new ModelAccountant();
		        $model->page = $this->page;
		        $model->tableName = $type;
		        $opts['type'] = PAYMENT_CATEGORY_SUPPLIER;
		        $ssp = $model->getSSPData($type, $opts);
		        break;
            case 'modal_catalogue':
                require_once 'model_catalogue.php';
                $model = new ModelCatalogue();
                $model->tableName = $type;
                $ssp = $model->getSSPData();
                $ssp['page'] = $this->page;
                break;
        }

        return $ssp;
    }

    public function getTableData($type = 'general', $opts = [])
    {
        $data = $this->getSSPData($type, $opts);

        switch ($type) {
	        case 'orders_payments':
	        case 'general':
	            $selects = $this->getSelects($data);
				break;
	        case 'modal_catalogue':
                $cache = new Cache();
                $selects = $cache->getOrSet($type, function() use($data) {
                    $model = new ModelCatalogue();
                    return $model->getSelects($data);
                });
                break;
        }

        return array_merge($data, $selects);
    }

}