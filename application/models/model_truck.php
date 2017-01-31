<?php

class ModelTruck extends Model
{

    var $truck_columns = [
        array('dt' => 0, 'db' => "trucks_items.truck_item_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/product?id=',
                trucks_items.product_id,
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
                trucks_items.truck_item_id,
                '\" data-name=\"amount\" data-value=\"',
                IFNULL(trucks_items.amount, ''),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Quantity\">',
                    IFNULL(CONCAT(trucks_items.amount, ' ', products.units), ''),
                '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-number_of_packs\" data-pk=\"',
                trucks_items.truck_item_id,
                '\" data-name=\"number_of_packs\" data-value=\"',
                IFNULL(trucks_items.number_of_packs, ''),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Number of Packs\">',
                    IFNULL(trucks_items.number_of_packs, ''),
                '</a>')"),
        array('dt' => 4, 'db' => "IFNULL(CAST(products.purchase_price as decimal(64, 2)), '')"),
        array('dt' => 5, 'db' => "IFNULL(CAST(products.purchase_price * trucks_items.amount as decimal(64, 2)), '')"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-item_status\" data-pk=\"',
                trucks_items.truck_item_id,
                '\" data-name=\"item_status\" data-value=\"',
                IFNULL(order_items.item_status, suppliers_orders_items.item_status),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Choose Item Status\">',
                    IFNULL(order_items.item_status, suppliers_orders_items.item_status),
                '</a>')"),
        array('dt' => 7, 'db' => "products.weight"),
        array('dt' => 8, 'db' => "orders.downpayment_rate"),
        array('dt' => 9, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 10, 'db' => "managers.first_name"),
        array('dt' => 11, 'db' => "CONCAT('<a href=\"/order?id=',
                order_items.order_id,
                '\">', order_items.order_id, '</a>')"),
        array('dt' => 12, 'db' => "CONCAT('<a href=\"/suppliers_order?id=',
                suppliers_orders_items.order_id,
                '\">', suppliers_orders_items.order_id, '</a>')"),
        array('dt' => 13, 'db' => "clients.name"),
        array('dt' => 14, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-import_VAT\" data-pk=\"',
                trucks_items.truck_item_id,
                '\" data-name=\"import_VAT\" data-value=\"',
                IFNULL(CAST(trucks_items.import_VAT as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Import VAT\">',
                    IFNULL(CAST(trucks_items.import_VAT as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 15, 'db' => "CONCAT('<a href=\"javascript:;\" 
                class=\"x-editable x-import_brokers_price\" data-pk=\"',
                trucks_items.truck_item_id,
                '\" data-name=\"import_brokers_price\" data-value=\"',
                IFNULL(CAST(trucks_items.import_brokers_price as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Import Brokers Price\">',
                    IFNULL(CAST(trucks_items.import_brokers_price as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 16, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-import_tax\" data-pk=\"',
                trucks_items.truck_item_id,
                '\" data-name=\"import_tax\" data-value=\"',
                IFNULL(CAST(trucks_items.import_tax as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Customs Price\">',
                    IFNULL(CAST(trucks_items.import_tax as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 17, 'db' => "CONCAT('<a href=\"javascript:;\" class=\"x-editable x-delivery_price\" data-pk=\"',
                trucks_items.truck_item_id,
                '\" data-name=\"delivery_price\" data-value=\"',
                IFNULL(CAST(trucks_items.delivery_price as decimal(64, 2)), 0),
                '\" data-url=\"/truck/change_item_field\" data-original-title=\"Enter Delivery Price\">',
                    IFNULL(CAST(trucks_items.delivery_price as decimal(64, 2)), 0),
                '</a>')"),
        array('dt' => 18, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/truck/delete_order_item?order_id=', trucks_items.truck_id, '&order_item_id=', trucks_items.truck_item_id,
                        '\" onclick=\"return confirm(\'Are you sure to delete the item?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                        IF(order_items.item_status != 'Arrived' OR suppliers_orders_items.item_status != 'Arrived',
                        CONCAT('<a href=\"/truck/put_item_to_warehouse?truck_item_id=', trucks_items.truck_item_id, 
                        '\" onclick=\"return confirm(\'Are you sure to put to warehouse the item?\')\"><span class=\'glyphicon
                         glyphicon-home\' title=\'Put to Warehouse\'></span></a>'),
                         ''),
                '</div>')")
    ];

    var $suppliers_orders_column_names = [
        'Supplier Order ID',
        'Supplier Order ID',
        'Product',
        'Date of Order (Supplier)',
        'Supplier Release Date',
        'Truck ID',
        'Supplier Departure Date',
        'Warehouse Arrival Date',
        'Manager Order ID',
        'Manager',
        'Brand',
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

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTTrucks($truck_id, $input)
    {
        $trucks_table = 'trucks_items
    left join products on trucks_items.product_id = products.product_id
    left join brands on products.brand_id = brands.brand_id
    left join colors on products.color_id = colors.color_id
    left join colors as colors2 on products.color2_id = colors2.color_id
    left join constructions on products.construction_id = constructions.construction_id
    left join wood on products.wood_id = wood.wood_id
    left join grading on products.grading_id = grading.grading_id
    left join patterns on products.pattern_id = patterns.pattern_id
    left join trucks on trucks.id = trucks_items.truck_id
    left join suppliers_orders_items on trucks_items.suppliers_order_item_id = suppliers_orders_items.order_item_id
    left join order_items on (order_items.order_item_id = suppliers_orders_items.managers_order_item_id)
    left join orders on order_items.order_id = orders.order_id
    left join clients on orders.client_id = clients.client_id
    left join users as managers on orders.sales_manager_id = managers.user_id';

        $this->sspComplex($trucks_table, "trucks_items.truck_item_id", $this->truck_columns,
            $input, null, "trucks_items.truck_id = $truck_id");
    }

    public function getTruckStatus($truck_id)
    {
        $truck = $this->getFirst("SELECT status FROM trucks WHERE id = $truck_id");
        return isset($truck['status']) ? $truck['status'] : '';
    }

    function addTruckItem($products, $truck_id = 0)
    {
        // Если заказ новый
        if (!$truck_id) {
            $this->insert("INSERT INTO trucks (truck_items_count, supplier_departure_date) VALUES (0, NOW())");
            $truck_id = $this->insert_id;
        }
        if ($truck_id) {
            $order_items_count = 0;
            foreach ($products as $order_item_id) {
                $order_item = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_item_id = $order_item_id");

                $productId = $order_item['product_id'];
                $amount = $order_item['amount'] ? $order_item['amount'] : 1;

                $product = $this->getFirst("SELECT * FROM products WHERE product_id = $productId");
                $number_of_packs = $product['amount_in_pack'] != null ? 0 : 1;

                $this->insert("INSERT INTO trucks_items (truck_id, product_id, amount,
                                number_of_packs, total_price, suppliers_order_item_id)
                                VALUES ($truck_id, $productId, $amount, 
                                $number_of_packs, 0, $order_item_id)");

                if ($order_item['managers_order_item_id']) {
                    $this->update("UPDATE order_items SET item_status = 'On the way' 
                      WHERE order_item_id = ${order_item['managers_order_item_id']}");
                }
                else {
                    $this->update("UPDATE suppliers_orders_items SET item_status = 'On the way' WHERE order_item_id = $order_item_id");
                }


                $order_items_count += $amount;
            }
            // Обновим количество товаров
            $order = $this->getFirst("SELECT * FROM trucks WHERE id = $truck_id");
            $order_items_count += intval($order['truck_items_count']);
            $this->update("UPDATE trucks 
                              SET truck_items_count = $order_items_count WHERE id = $truck_id");
            $this->updateItemsStatus($truck_id);
        }
        return $truck_id;
    }

    function getActiveTrucks()
    {
        $trucks = $this->getAssoc("SELECT id FROM trucks"); // add where
        $truckIds = [];
        if (!empty($trucks)) {
            foreach ($trucks as $truck) {
                $truckIds[] = $truck['id'];
            }
        }
        return $truckIds;
    }


    function getDTOrderItems($order_id, $input)
    {
        $table = $this->full_products_table . ' join trucks_items on products.product_id = trucks_items.product_id';

        $this->sspComplex($table, "trucks_items.truck_item_id", $this->truck_columns,
            $input, null, "trucks_items.truck_id = $order_id");
    }

    function putToTheWarehouse($truckId)
    {
//        $this->update("UPDATE trucks SET warehouse_arrival_date = NOW() WHERE id = $truckId");
        $truckItems = $this->getAssoc("SELECT * FROM trucks_items WHERE truck_id = $truckId");
        foreach ($truckItems as $truckItem) {
            if (!intval($truckItem['warehouse_arrival_date']))
                $this->putItemToWarehouse($truckItem['truck_item_id']);
        }
    }

    function putItemToWarehouse($itemId)
    {
        if (!$itemId)
            return false;

        $truckItem = $this->getFirst("SELECT * FROM trucks_items WHERE truck_item_id = $itemId");

        $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${truckItem['product_id']}");

        $totalPrice = $truckItem['amount'] * $product['purchase_price'];

        $buyAndExpenses = $truckItem['import_tax'] + $truckItem['import_brokers_price'] + $truckItem['import_VAT'];

        $buyAndExpenses *= $truckItem['amount'];

        $warehouseId = $this->insert("INSERT INTO products_warehouses (product_id, warehouse_id, amount, total_price,
 	      buy_and_taxes) 
          VALUES (${truckItem['product_id']}, 1, ${truckItem['amount']}, $totalPrice, $buyAndExpenses)");

        $this->update("UPDATE trucks_items SET warehouse_arrival_date = NOW() WHERE truck_item_id = $itemId");

        $this->changeItemStatus($itemId, 'On Stock');

        return $warehouseId;
    }

    public function getOrder($order_id)
    {
        return $this->getById('trucks', 'id', $order_id);
    }

    function changeStatus($order_id, $status)
    {
        $this->update("UPDATE trucks SET status = '$status' WHERE id = $order_id");
    } // here

    function cancelOrder($order_id, $cancel_reason)
    {
        $this->update("UPDATE trucks SET order_status = 'Cancelled', cancel_reason = '$cancel_reason' WHERE id = $order_id");
    }

    function addOrderItem($order_id, $product_ids)
    {
        foreach ($product_ids as $product_id) {
            $product = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE order_item_id = $product_id");
            $number_of_packs = $product['amount'] != null ? 0 : 1;
            $amount = $product['amount'] ? $product['amount'] : 1;

            $this->insert("INSERT INTO trucks_items (truck_id, product_id, amount,
                                number_of_packs, total_price, suppliers_order_item_id)
                VALUES ($order_id, ${product['product_id']}, $amount, $number_of_packs, 0, $product_id)");

            if ($product['managers_order_item_id']) {
                $this->update("UPDATE order_items SET item_status = 'On the way' 
                      WHERE order_item_id = ${product['managers_order_item_id']}");
            }
            else {
                $this->update("UPDATE suppliers_orders_items SET item_status = 'On the way' WHERE order_item_id = $product_id");
            }

            $order = $this->getFirst("SELECT * FROM trucks WHERE id = $order_id");

            $order_items_count = $order['truck_items_count'] + $product['amount'];
            $this->update("UPDATE trucks 
                SET truck_items_count = $order_items_count
                WHERE id = $order_id");
        }
        $this->updateItemsStatus($order_id);

    }

    function deleteOrderItem($order_id, $order_item_id)
    {
        $order_item = $this->getFirst("SELECT * FROM trucks_items WHERE truck_item_id = $order_item_id");

        $supplierOrderItem = $this->getFirst("SELECT * FROM suppliers_orders_items 
          WHERE order_item_id = ${order_item['suppliers_order_item_id']}");
        if ($managerOrderItem = $supplierOrderItem['managers_order_item_id']) {
            $this->update("UPDATE order_items SET item_status = 'Produced' WHERE order_item_id = $managerOrderItem");
        } else {
            $this->update("UPDATE suppliers_orders_items SET item_status = 'Produced'
              WHERE order_item_id = ${order_item['suppliers_order_item_id']}");
        }

        $this->delete("DELETE FROM trucks_items WHERE truck_item_id = $order_item_id");

        $order = $this->getFirst("SELECT * FROM trucks WHERE id = $order_id");
        $order_items_count = $order['truck_items_count'] - $order_item['amount'];

        $this->update("UPDATE trucks 
                SET truck_items_count = $order_items_count 
                WHERE id = $order_id");

        $this->updateItemsStatus($order_id);

    }

    public function updateField($order_id, $field, $new_value)
    {
        $old_order = $this->getFirst("SELECT * FROM trucks WHERE id = $order_id");
        $result = $this->update("UPDATE `trucks` SET `$field` = '$new_value' WHERE id = $order_id");

        return $result;
    }

    public function updateItemField($order_item_id, $field, $new_value)
    {
        $result = false;
        $old_order_item = $this->getFirst("SELECT * FROM trucks_items WHERE truck_item_id = $order_item_id");
        $supplier_order = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE 
          order_item_id = ${old_order_item['suppliers_order_item_id']}");
        if ($field == 'item_status') {
            if ($supplier_order && !empty($supplier_order) && $supplier_order['managers_order_item_id']) {
                $result = $this->update("UPDATE `order_items` SET item_status = '$new_value' WHERE 
              order_item_id = ${supplier_order['managers_order_item_id']}");
            }
            else {
                $result = $this->update("UPDATE `suppliers_orders_items` SET `$field` = '$new_value' 
                  WHERE order_item_id = ${old_order_item['suppliers_order_item_id']}");
            }

//        Изменим статус самого объекта
            $this->updateItemsStatus($old_order_item['truck_id']);
        }
        else
            $result = $this->update("UPDATE `trucks_items` SET `$field` = '$new_value' WHERE truck_item_id = $order_item_id");

        $new_order_item = $this->getFirst("SELECT * FROM trucks_items WHERE truck_item_id = $order_item_id");


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
        $this->update("UPDATE trucks_items 
            SET amount = $amount, number_of_packs = $number_of_packs, total_price = $total_price, reduced_price = $reduced_price, manager_bonus = $manager_bonus
            WHERE truck_item_id = $order_item_id");

        $order_id = $new_order_item['order_id'];
        $order = $this->getFirst("SELECT * FROM trucks WHERE id = $order_id");
        $total_price = $order['total_price'] - $old_order_item['reduced_price'] + $reduced_price;
        $manager_bonus = $order['manager_bonus'] - $old_order_item['manager_bonus'] + $manager_bonus;
        $total_commission = $order['commission_rate'] / 100 * $order['total_price'];
        $this->update("UPDATE trucks 
                SET total_price = $total_price, manager_bonus = $manager_bonus, total_commission = $total_commission
                WHERE id = $order_id");

        return $result;
    }


    public function updateItemsStatus($truckId)
    {
        $truckItems = $this->getAssoc("SELECT * FROM trucks_items WHERE truck_id = $truckId");
        $status=10;
        if (!empty($truckItems))
            foreach ($truckItems as $truckItem) {
                $supplierOrderItem = $this->getFirst("SELECT * FROM suppliers_orders_items 
                    WHERE order_item_id = ${truckItem['suppliers_order_item_id']}");

                if ($supplierOrderItem['item_status'] && !$supplierOrderItem['managers_order_item_id']) {
                    $itemStatus = $supplierOrderItem['item_status'];
                }
                else {
                    $managerOrderItem = $this->getFirst("SELECT item_status FROM order_items 
                        WHERE order_item_id = ${supplierOrderItem['managers_order_item_id']}");
                    $itemStatus = $managerOrderItem['item_status'];
                }
                $newStatus = array_search($itemStatus, $this->statuses);
                if ($newStatus < $status)
                    $status = $newStatus;
            }
        $orderStatus = isset($this->statuses[$status]) ? $this->statuses[$status] : '';
        $this->update("UPDATE trucks 
                SET status = '$orderStatus' WHERE id = $truckId");
    }

    public function changeItemStatus($item_id, $status)
    {
        $truckItem = $this->getFirst("SELECT * FROM trucks_items WHERE truck_item_id = $item_id");

        $supplier = $this->getFirst("SELECT * FROM suppliers_orders_items WHERE 
              order_item_id = ${truckItem['suppliers_order_item_id']}");

        if ($supplier['managers_order_item_id']) {
            $this->update("UPDATE order_items SET item_status = '$status' 
                  WHERE order_item_id = ${supplier['managers_order_item_id']}");
        }
        else {
            $this->update("UPDATE suppliers_orders_items SET item_status = '$status' 
                  WHERE order_item_id = ${truckItem['suppliers_order_item_id']}");
        }
        $this->updateItemsStatus($truckItem['truck_id']);
    }

    public function getStatusList()
    {
        $statusList = [];
        $statuses = array_slice($this->statuses, 5, 2);
        foreach ($statuses as $status) {
            $item = new stdClass();
            $item->value = $status;
            $item->text = $status;
            $statusList[] = $item;
        }
        return $statusList;
    }

    public function getDelivery($truck_id)
    {
        if (!$truck_id)
            return [];

        $transportOfCurrentTruck = $this->getFirst("SELECT transportation_company_id FROM trucks 
                                                      WHERE id = $truck_id");
        $truckItems = $this->getAssoc("SELECT delivery_price FROM trucks_items WHERE truck_id = $truck_id");
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
        $truckItems = $this->getAssoc("SELECT import_tax, import_VAT, import_brokers_price FROM trucks_items 
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
    function getSums()
    {
        $truckItems = $this->getAssoc("SELECT * FROM trucks_items");

        $weight = 0;
        $packsNumber = 0;
        $totalPrice = 0;
        if (!empty($truckItems)) {
            foreach ($truckItems as $truckItem) {
                $product = $this->getFirst("SELECT weight FROM products WHERE product_id = ${truckItem['product_id']}");
                $weight += $product['weight'] !== null ? $product['weight'] : 0;
                $packsNumber += $truckItem['number_of_packs'];
                $totalPrice += $truckItem['total_price'];
            }
        }
        return [
            'weight' => $weight,
            'number_of_packs' => $packsNumber,
            'totalPrice' => $totalPrice
        ];
    }
}