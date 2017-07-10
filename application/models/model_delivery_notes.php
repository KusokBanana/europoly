<?php


class ModelDelivery_notes extends Model
{


    public function __construct()
    {
        $this->connect_db();
    }


    var $tableNames = ['table_delivery_notes', 'table_delivery_note'];


    var $delivery_notes_table = 'delivery_note '.
                 'LEFT JOIN delivery_note_items ON (delivery_note.id = delivery_note_items.note_id) '.
                 'LEFT JOIN order_items ON (delivery_note_items.order_item_id = order_items.item_id) '.
                 'LEFT JOIN orders ON (orders.order_id = order_items.manager_order_id) '.
                 'LEFT JOIN products ON (order_items.product_id = products.product_id) '.
                 'LEFT JOIN clients as client ON (orders.client_id = client.client_id) '.
                 'LEFT JOIN clients as commission on (orders.commission_agent_id = commission.client_id)'.
                 'LEFT JOIN users as managers on orders.sales_manager_id = managers.user_id '.
                 'LEFT JOIN legal_entities ON (orders.legal_entity_id = legal_entities.legal_entity_id) ';

    var $delivery_notes_columns = array(
        array('dt' => 0, 'db' => 'delivery_note.id'),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"\delivery_notes/view?id=', delivery_note.id, '\">', 
            delivery_note.id, '</a>')"),
        array('dt' => 2, 'db' => "delivery_note.date"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/order?id=', order_items.manager_order_id, '\">', 
            IFNULL(orders.visible_order_id, orders.order_id), '</a>')"),
        array('dt' => 4, 'db' => 'CAST(((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount/100) as decimal(64, 2))'),
        array('dt' => 5, 'db' => 'products.sell_price_currency'),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', 
            client.final_name, '</a>')"),
        array('dt' => 7, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', 
            commission.final_name, '</a>')"),
        array('dt' => 8, 'db' => 'legal_entities.name'),
        array('dt' => 9, 'db' => "CONCAT('<a href=\"/sales_manager?id=', orders.sales_manager_id, '\">', 
            managers.first_name, ' ', managers.last_name, '</a>')"),
    );

    var $delivery_notes_columns_names = array(
        '_id',
        'Delivery Note ID',
        'Date',
        'Order Id',
        'Sum',
        'Currency',
        'Customer',
        'Commission Agent',
        'Legal Entity Evropoly',
        'Responsible Manager',
    );

    var $columns = [
        array('dt' => 0, 'db' => "order_items.item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/product?id=',
                products.product_id,
                '\">', IFNULL(products.visual_name, 'Enter Visual Name!'),
                '</a>')"),
        array('dt' => 2, 'db' => "CONCAT(CAST(order_items.amount as decimal(64, 3)), ' ', IFNULL(products.units, ''))"),
        array('dt' => 3, 'db' => "CONCAT(CAST(order_items.number_of_packs as decimal(64, 3)), ' ', 
                                        IFNULL(products.packing_type, ''))"),
        array('dt' => 4, 'db' => "CONCAT(IF(products.units = 'm2' AND products.length NOT LIKE '%-%' 
                                                                        AND products.width NOT LIKE '%-%',
                                        IF(products.width = NULL, 'Width undefined', 
                                        IF(products.length = NULL, 'Length undefined', 
                                            CAST((order_items.amount * 1000 * 1000) / (products.width * products.length) 
                                            as decimal(64, 2)))), 'n/a'), '')"),
        array('dt' => 5, 'db' => "IFNULL(CAST(order_items.purchase_price as decimal(64, 2)), '')"),
        array('dt' => 6, 'db' => "IFNULL(CAST(order_items.purchase_price * order_items.amount as decimal(64, 2)), '')"),
        array('dt' => 7, 'db' => "IFNULL(CAST(order_items.sell_price as decimal(64, 2)), '')"),
        array('dt' => 8, 'db' => "CONCAT(CAST(order_items.discount_rate as decimal(64, 3)), '%')"),
        array('dt' => 9, 'db' => "CAST(order_items.sell_price * (100 - order_items.discount_rate)/100 as decimal(64, 2))"),
        array('dt' => 10, 'db' => "CAST(((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount/100) as decimal(64, 2))"),
        array('dt' => 11, 'db' => "CONCAT(CAST(order_items.commission_rate as decimal(64, 2)), '%')"),
        array('dt' => 12, 'db' => "CAST(order_items.commission_agent_bonus as decimal(64, 2))"),
        array('dt' => 13, 'db' => "CAST(order_items.manager_bonus_rate as decimal(64, 2))"),
        array('dt' => 14, 'db' => "CAST(order_items.manager_bonus as decimal(64, 2))"),
        array('dt' => 15, 'db' => "status.name"),
        array('dt' => 16, 'db' => "CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the item?\" 
                                    href=\"/delivery_notes/delete_item?item_id=', delivery_note_items.id, 
                                    '&order_item_id=', order_items.item_id, '\" 
                                    class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                    data-singleton=\"true\">
                                        <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                </a>')"),
    ];

    function getTable($isWithNote = true)
    {

        if ($isWithNote) {
            $table = 'delivery_note_items ' .
                'left join order_items on order_items.item_id = delivery_note_items.order_item_id ' .
                'left join products on order_items.product_id = products.product_id ' .
                'left join orders on order_items.manager_order_id = orders.order_id ' .
                $this->full_products_table_addition . ' ' .
                'left join items_status as status on order_items.status_id = status.status_id';
        } else {
            $table = 'order_items ' .
                'left join products on order_items.product_id = products.product_id ' .
                'left join orders on order_items.manager_order_id = orders.order_id ' .
                $this->full_products_table_addition . ' ' .
                'left join items_status as status on order_items.status_id = status.status_id';
        }

        return $table;

    }

    function getDTOrderItems($note_id, $input, $ids = null)
    {

        $where = ['order_items.is_deleted = 0'];

        if ($ids) {
            $table = $this->getTable(false);
            $where[] = "order_items.item_id IN ($ids)";
            $primary = 'order_items.item_id';
            unset($this->columns[16]);
        } else {
            $table = $this->getTable();
            $where[] = "delivery_note_items.note_id = $note_id";
            $primary = "delivery_note_items.id";
        }

        $roles = new Roles();

        $columns = $roles->returnModelColumns($this->columns, 'order');

        return $this->sspComplex($table, $primary, $columns, $input, null, $where);
    }

    var $delivery_note_items_names = [
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


    function getDt($input, $printOpt)
    {

        if ($this->user->role_id == ROLE_SALES_MANAGER) {

        }

        $columns = $this->getColumns($this->delivery_notes_columns, 'deliveryNotes', $this->tableNames[0]);

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->delivery_notes_columns_names,
            'db_table' => $this->delivery_notes_table,
            'page' => 'deliveryNotes',
            'table_name' => $this->tableNames[0],
            'primary' => 'delivery_note.id',
        ];

        if ($printOpt) {

//            $printOpt['where'] = $this->whereCondition;  2564 1883 1235 876
            echo $this->printTable($input, $ssp, $printOpt);
            return true;

        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'],
            $ssp['columns'], $input, null, null);
    }

    function getSelectsModal()
    {

        $table = $this->getTable();

        $roles = new Roles();

        $columns = $roles->returnModelColumns($this->columns, 'order');
        $tableNames = $this->getColumns($columns,
            'deliveryNotes', 'table_order_items_new_modal', true);
        $ssp = $this->getSspComplexJson($table, "order_items.item_id",
            $tableNames, null, null, null);

        $columns = $this->getColumns($this->columns, 'order', 'table_order_items_new_modal');
        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['Order Id', 'Delivery Note ID'];

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

    function getSelects()
    {
        $tableNames = $this->getColumns($this->delivery_notes_columns,
            'deliveryNotes', $this->tableNames[0], true);

        $ssp = $this->getSspComplexJson($this->delivery_notes_table, "order_items.item_id",
            $tableNames, null, null, null);
        $columns = $this->getColumns($this->delivery_notes_columns_names, 'deliveryNotes', $this->tableNames[0]);
        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['Order Id', 'Delivery Note ID'];

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

//    function getOrder($orderItemId, $noteId = false)
//    {
//
//        if (!$orderItemId) {
//            $note = $this->getDeliveryNote($noteId);
//            $orderItemId = $note['order_item_id'];
//        }
//
//        $items = $this->getFirst("SELECT manager_order_id FROM order_items WHERE item_id = $orderItemId");
//        if ($items) {
//            return $this->getFirst("SELECT * FROM orders WHERE order_id = ${items['manager_order_id']}");
//        }
//    }

    function getProducts($order_id, $note_id)
    {
        $notes = $this->getAssoc("SELECT * FROM delivery_note_items WHERE note_id = $note_id");
        $ids = [];
        foreach ($notes as $note) {
            $ids[] = $note['order_item_id'];
        }
        $ids = join(',', $ids);
        $notIn = '';
        if ($ids)
            $notIn = "AND item_id NOT IN ($ids)";
        $items = $this->getAssoc("SELECT * FROM order_items WHERE manager_order_id = $order_id 
          $notIn AND status_id = " . ON_STOCK);

        $res = [];
        foreach ($items as $item) {
            $res[] = $item['item_id'];
        }
        array_unique($res);
        return join(',', $res);
    }

    function deleteItem($item_id)
    {

        $item = $this->getFirst("SELECT * FROM delivery_note_items WHERE id = $item_id");
        if ($item) {
            $order_item = $item['order_item_id'];
            $this->update("UPDATE order_items SET status_id = " . ON_STOCK .
                " WHERE item_id = $order_item");
            $this->delete("DELETE FROM delivery_note_items WHERE id = $item_id");
        }

    }

    function add($items, $note_id)
    {

        if ($items) {

            $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE item_id IN ($items)");

            foreach ($orderItems as $orderItem) {
                $itemId = $orderItem['item_id'];
                $this->update("UPDATE order_items SET status_id = " . EXPECTS_ISSUE . " WHERE item_id = $itemId");
                $this->insert("INSERT INTO delivery_note_items (order_item_id, note_id) VALUES ($itemId, $note_id)");
            }

        }

    }

    function issue($note_id)
    {

        $items = $this->getAssoc("SELECT * FROM delivery_note_items WHERE note_id = $note_id");

        $order_items = [];
        foreach ($items as $item) {
            $order_items[] = $item['order_item_id'];
        }

        if (!empty($order_items)) {
            $order_items = join(',', $order_items);
            $this->update("UPDATE order_items SET status_id = " . ISSUED . " WHERE item_id IN ($order_items)");
        }

    }

    public function getDocuments($note_id)
    {
        $docs = [
            [
                'href' => "/delivery_notes/print_doc?note_id=$note_id",
                'name' => 'Print Delivery Note'
            ],
        ];
        return $docs;
    }

    public function printDoc($note_id, $log = [])
    {
        $fileName = 'expects_issue';

        if ($note_id) {
            $notes = $this->getAssoc("SELECT * FROM delivery_note_items WHERE note_id = $note_id");
            $ids = [];
            foreach ($notes as $note) {
                $ids[] = $note['order_item_id'];
            }
            $ids = join(',', $ids);
            $where[] = "order_items.item_id IN ($ids)";
        }

        $where[] = 'order_items.is_deleted = 0';

        $where = implode(' AND ', $where);

        $orderItems = $this->getAssoc("SELECT * FROM order_items WHERE $where");
        $order_id = $orderItems[0]['manager_order_id'];

        if (!empty($orderItems)) {

            $array = $this->getProductsDataArrayForDocPrint($orderItems, true);

            $products = $array['products'];
            $values = $array['values'];

            require dirname(__FILE__) . "/../../assets/PHPWord_CloneRow-master/PHPWord.php";
            $phpWord = new PHPWord();
            $docFile = dirname(__FILE__) . "/../../docs/templates/$fileName.docx";

            $values['date'] = date('d.m.Y');
            $prodIds = [];
            foreach ($orderItems as $orderItem) {
                $prodIds[] = $orderItem['product_id'];
            }
            $values['product_id'] = join(', ', $prodIds);
            $values['note_id'] = $note_id;

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

}