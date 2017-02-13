<?php

include_once 'model_managers_orders.php';

class ModelWarehouse extends ModelManagers_orders
{
    var $products_warehouses_table = "order_items as products_warehouses
            left join products on products_warehouses.product_id = products.product_id
            left join brands on products.brand_id = brands.brand_id
            left join orders on products_warehouses.manager_order_id = orders.order_id
            left join suppliers_orders on products_warehouses.supplier_order_id = suppliers_orders.order_id
            left join trucks on products_warehouses.truck_id = trucks.id
            left join clients as client on (orders.client_id = client.client_id)
            left join clients as commission on (orders.commission_agent_id = commission.client_id)
            left join items_status as status on products_warehouses.status_id = status.status_id
            left join users as managers on orders.sales_manager_id = managers.user_id
            left join warehouses on products_warehouses.warehouse_id = warehouses.warehouse_id";

    var $product_warehouses_columns = array(
        array('dt' => 0, 'db' => 'products_warehouses.item_id'),
        array('dt' => 1, 'db' => 'products.article'),
        array('dt' => 2, 'db' => 'CONCAT(\'<a href="/product?id=\', products.product_id, \'">\', IFNULL(products.name, \'no name\'), \'</a>\')'),
        array('dt' => 3, 'db' => 'CONCAT(\'<a href="/brand?id=\', brands.brand_id, \'">\', IFNULL(brands.name, \'no name\'), \'</a>\')'),
        array('dt' => 4, 'db' => 'CONCAT(\'<a href="javascript:;" class="x-editable x-warehouse_id" 
            data-pk="\', products_warehouses.item_id ,\'" 
            data-name="warehouse_id" data-value="\', products_warehouses.warehouse_id, \'" data-url="/warehouse/change_item_field" 
            data-original-title="Change Warehouse">\', IFNULL(warehouses.name, \'no name\'), \'</a>,
            <a href="/warehouse?id=\', products_warehouses.warehouse_id, \'"><i class="glyphicon glyphicon-link"></i></a>\')'),
        array('dt' => 5, 'db' => 'products_warehouses.amount'),
        array('dt' => 6, 'db' => 'products.units'),
        array('dt' => 7, 'db' => "products_warehouses.number_of_packs"),
        array('dt' => 8, 'db' => "products.weight * products_warehouses.number_of_packs"),
        array('dt' => 9, 'db' => 'products_warehouses.buy_price'),
        array('dt' => 10, 'db' => 'products_warehouses.buy_and_taxes'),
        array('dt' => 11, 'db' => 'products_warehouses.sell_price'),
        array('dt' => 12, 'db' => 'products_warehouses.dealer_price'),
        array('dt' => 13, 'db' => 'products_warehouses.total_price'),
        array('dt' => 14, 'db' => 'CAST((products_warehouses.purchase_price * products_warehouses.amount + products_warehouses.import_vat + products_warehouses.import_brokers_price + products_warehouses.import_tax + products_warehouses.delivery_price) as decimal(64, 2))'),
        array('dt' => 15, 'db' => 'CAST((products_warehouses.purchase_price * products_warehouses.amount + 
        products_warehouses.import_vat + products_warehouses.import_brokers_price + products_warehouses.import_tax +
         products_warehouses.delivery_price) / products_warehouses.amount as decimal(64, 2))'),
        array('dt' => 16, 'db' => "CONCAT('<a href=\"/order?id=', products_warehouses.manager_order_id, '\">', 
            products_warehouses.manager_order_id,
            IF(products_warehouses.reserve_since_date IS NULL, '', ' (reserved)'), '</a>')"),
        array('dt' => 17, 'db' => "CONCAT(managers.first_name, ' ', managers.last_name, '<a href=\"/sales_manager?id=',
            orders.sales_manager_id, '\"><i class=\"glyphicon glyphicon-link\"></i></a>')"),
        array('dt' => 18, 'db' => "orders.start_date"),
        array('dt' => 19, 'db' => "status.name"),
        array('dt' => 20, 'db' => "products_warehouses.purchase_price"),
        array('dt' => 21, 'db' => "products_warehouses.purchase_price * products_warehouses.number_of_packs"),
        array('dt' => 22, 'db' => "products_warehouses.sell_price"),
        array('dt' => 23, 'db' => "products_warehouses.sell_price * products_warehouses.number_of_packs"),
        array('dt' => 24, 'db' => "orders.total_downpayment"),
        array('dt' => 25, 'db' => "orders.downpayment_rate"),
        array('dt' => 26, 'db' => "orders.expected_date_of_issue"),
        array('dt' => 27, 'db' => "CONCAT('<a href=\"/suppliers_order?id=', products_warehouses.supplier_order_id, '\">',
            products_warehouses.supplier_order_id, '</a>')"),
        array('dt' => 28, 'db' => "suppliers_orders.supplier_date_of_order"),
        array('dt' => 29, 'db' => "suppliers_orders.release_date"),
        array('dt' => 30, 'db' => "CONCAT('<a href=\"/truck?id=', products_warehouses.truck_id, '\">',
            products_warehouses.truck_id, '</a>')"),
        array('dt' => 31, 'db' => "trucks.supplier_departure_date"),
        array('dt' => 32, 'db' => "products_warehouses.warehouse_arrival_date"),
        array('dt' => 33, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.name, '</a>')"),
        array('dt' => 34, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.name, '</a>')"),
        array('dt' => 35, 'db' => "products_warehouses.discount_rate"),
        array('dt' => 36, 'db' => "products_warehouses.reduced_price"),
        array('dt' => 37, 'db' => "products_warehouses.manager_bonus_rate"),
        array('dt' => 38, 'db' => "products_warehouses.manager_bonus"),
        array('dt' => 39, 'db' => "products_warehouses.commission_rate"),
        array('dt' => 40, 'db' => "products_warehouses.commission_agent_bonus"),
        array('dt' => 41, 'db' => "products_warehouses.production_date"),
        array('dt' => 42, 'db' => "IFNULL(CONCAT(products_warehouses.reserve_since_date, 
            ' - ',products_warehouses.reserve_till_date), '')"),
    );

    var $product_warehouses_column_names = [
        "Id",
        "Article",
        "Product",
        "Brand",
        "Warehouse",
        "Quantity",
        "Units",
        'Number of Packs',
        'Total Weight',
        "Buy Price",
        "Buy + Transport + Taxes",
        "Sell Price",
        "Dealer Price (-30%)",
        "Total Price",
        "Purchase Value + Expenses",
        "Purchase Price + Expenses",
        'Manager Order ID',
        'Manager',
        'Date of Order (Client)',
        'Status',
        'Purchase Price / Unit',
        'Total Purchase Price',
        'Sell Price / Unit',
        'Total Sell Price',
        'Downpayment',
        'Downpayment rate',
        'Client\'s expected date of issue',
        'Supplier Order ID',
        'Date of Order (Supplier)',
        'Supplier Release Date',
        'Truck ID',
        'Supplier Departure Date',
        'Warehouse Arrival Date',
        'Client',
        'Commission Agent',
        'Discount Rate',
        'Reduced Price',
        'Manager Bonus Rate',
        'Manager Bonus',
        'Commission Rate',
        'Commission Agent Bonus',
        'Production Date',
        'Reserve Period',
    ];

    function getDTProductsForWarehouses($input, $warehouse_id = 0, $type)
    {
        if ($warehouse_id) {
            $where = "products_warehouses.warehouse_id = $warehouse_id";
        } else {
            $where = 'products_warehouses.warehouse_id IS NOT NULL';
        }
        switch ($type) {
            case '':
                $where = '(' . $where . ' AND products_warehouses.manager_order_id IS NULL)';
                break;
            case 'issue':
                $where = '(' . $where . ' AND ((products_warehouses.manager_order_id IS NOT NULL AND '.
                'products_warehouses.reserve_since_date IS NULL) AND (products_warehouses.status_id = '.ON_STOCK.' OR '.
                'products_warehouses.status_id = '.EXPECTS_ISSUE.')))';
                break;
            case 'reserve':
                $where = '(' . $where . ' AND (products_warehouses.manager_order_id IS NOT NULL AND '.
                'products_warehouses.reserve_since_date IS NOT NULL))';
                break;
        }

        $this->sspComplex($this->products_warehouses_table, "products_warehouses.item_id", $this->product_warehouses_columns,
            $input, null, $where);
    }

    function getSelects($warehouse_id = 0)
    {
        if (!$warehouse_id) {
            $where = 'products_warehouses.warehouse_id IS NOT NULL';
        } else {
            $where = "products_warehouses.warehouse_id = $warehouse_id";
        }
        $ssp = $this->getSspComplexJson($this->products_warehouses_table, "products_warehouses.item_id",
            $this->product_warehouses_columns, null, null, $where);
        $columns = $this->product_warehouses_column_names;
        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['Id', 'Quantity', 'Buy Price', 'Buy + Transport + Taxes', 'Sell Price',
            'Dealer Price (-30%)', 'Total Price'];

        if (!empty($rowValues)) {
            $selects = [];
            foreach ($rowValues as $product) {
                foreach ($product as $key => $value) {
                    if (!$value || $value == null)
                        continue;
                    $name = $columns[$key];
                    if (in_array($name, $ignoreArray))
                        continue;

                    if (strpos($value, 'glyphicon') !== false) {
                        $value = preg_replace('/<a \w+[^>]+?[^>]+>(.*?)<\/a>/i', '', $value);
                    } else {
                        preg_match('/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i', $value, $match);
                        if (!empty($match) && isset($match[1])) {
                            $value = $match[1];
                        }
                    }

                    if ((isset($selects[$name]) && !in_array($value, $selects[$name])) || !isset($selects[$name]))
                        $selects[$name][] = $value;
                }
            }
            return ['selects' => $selects, 'rows' => $rowValues];
        }
    }

    function addProductsWarehouse($product_ids, $warehouse_id, $amount, $buy_price)
    {
        if ($product_ids)
            $product_ids = explode(',', $product_ids);
        else
            return false;

        $amount = $amount !== 'null' ? $amount : 0;
        $buy_price = $buy_price !== 'null' ? $buy_price : 0;

        foreach ($product_ids as $product_id) {
            $this->insert("INSERT INTO `order_items` (`product_id`, `warehouse_id`, `amount`, `buy_price`)
                    VALUES ($product_id, $warehouse_id, $amount, $buy_price)");
        }

//        $existing_pw = $this->getFirst("SELECT *
//                FROM products_warehouses
//                WHERE product_id = $product_id AND warehouse_id = $warehouse_id");
//        if ($existing_pw != null) {
//            $existing_amount = $existing_pw['amount'];
//            $new_new_amount = $existing_amount + $amount;
//            $buy_price = ($existing_pw['buy_price'] * $existing_amount + $buy_price * $amount) / $new_new_amount;
//            $buy_and_taxes = ($existing_pw['buy_and_taxes'] * $existing_amount + $buy_and_taxes * $amount) / $new_new_amount;
//            $sell_price = ($existing_pw['sell_price'] * $existing_amount + $sell_price * $amount) / $new_new_amount;
//            $dealer_price = ($existing_pw['dealer_price'] * $existing_amount + $dealer_price * $amount) / $new_new_amount;
//            $total_price = $existing_pw['total_price'] + $total_price;
//
//            $this->update("UPDATE products_warehouses
//                SET amount = $new_new_amount, buy_price = $buy_price, buy_and_taxes = $buy_and_taxes,
//                    sell_price = $sell_price, dealer_price = $dealer_price, total_price = $total_price
//                WHERE product_warehouse_id = ${existing_pw['product_warehouse_id']}");
//            return $existing_pw['product_warehouse_id'];
//        } else {
//            return $this->insert("INSERT INTO `products_warehouses` (`product_id`, `warehouse_id`, `amount`, `buy_price`, `buy_and_taxes`, `sell_price`, `dealer_price`, `total_price`)
//                    VALUES ($product_id, $warehouse_id, $amount, $buy_price, $buy_and_taxes, $sell_price, $dealer_price, $total_price)");
//        }
    }

    function getPrices($warehouse_id)
    {

        $buyPrice = 0;
        $sellPrice = 0;
        $buyAndExpenses = 0;
        $dealerPrice = 0;

        $where = ($warehouse_id) ? "WHERE warehouse_id = $warehouse_id" : "WHERE warehouse_id IS NOT NULL";
        $warehouseProducts = $this->getAssoc("SELECT * FROM order_items $where");
        if (!empty($warehouseProducts))
            foreach ($warehouseProducts as $warehouseProduct) {
                $amount = $warehouseProduct['amount'];
//                $product = $this->getFirst("SELECT * FROM products WHERE product_id = ${warehouseProduct['product_id']}");
                $buyPrice += $warehouseProduct['buy_price'] * $amount;
                $buyAndExpenses += $warehouseProduct['buy_and_taxes'] * $amount;
                $dealerPrice += $warehouseProduct['dealer_price'] * $amount;
                $sellPrice += $warehouseProduct['sell_price'] * $amount;
            }
        return [
            'buy' => $buyPrice,
            'buyAndExpenses' => $buyAndExpenses,
            'dealer' => $dealerPrice,
            'sellPrice' => $sellPrice,
        ];
    }

    public function getWarehousesIdNames()
    {
        return $this->getAssoc("SELECT warehouse_id as value, name as text FROM warehouses");
    }

    public function updateItemField($warehouse_item_id, $field, $new_value)
    {
        $old_order_item = $this->getFirst("SELECT * FROM order_items WHERE item_id = $warehouse_item_id");

        return $this->update("UPDATE `order_items` SET `$field` = '$new_value' WHERE item_id = $warehouse_item_id");

    }
}