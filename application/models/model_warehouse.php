<?php

include_once 'model_managers_orders.php';

class ModelWarehouse extends ModelManagers_orders
{
    var $products_warehouses_table = "order_items as products_warehouses
            left join products on products_warehouses.product_id = products.product_id
            left join brands on products.brand_id = brands.brand_id
            left join warehouses on products_warehouses.warehouse_id = warehouses.warehouse_id";

    function getDTProductsForAllWarehouses($input)
    {
        $product_warehouses_columns = array(
            array('dt' => 0, 'db' => 'products_warehouses.item_id'),
            array('dt' => 1, 'db' => 'products.article'),
            array('dt' => 2, 'db' => 'CONCAT(\'<a href="/product?id=\', products.product_id, \'">\', IFNULL(products.name, \'no name\'), \'</a>\')'),
            array('dt' => 3, 'db' => 'CONCAT(\'<a href="/brand?id=\', brands.brand_id, \'">\', IFNULL(brands.name, \'no name\'), \'</a>\')'),
            array('dt' => 4, 'db' => 'CONCAT(\'<a href="/warehouse?id=\', warehouses.warehouse_id, \'">\', IFNULL(warehouses.name, \'no name\'), \'</a>\')'),
            array('dt' => 5, 'db' => 'products_warehouses.amount'),
            array('dt' => 6, 'db' => 'products.units'),
            array('dt' => 7, 'db' => 'products_warehouses.buy_price'),
            array('dt' => 8, 'db' => 'products_warehouses.buy_and_taxes'),
            array('dt' => 9, 'db' => 'products_warehouses.sell_price'),
            array('dt' => 10, 'db' => 'products_warehouses.dealer_price'),
            array('dt' => 11, 'db' => 'products_warehouses.total_price')
        );
        $where = 'products_warehouses.warehouse_id IS NOT NULL';
        $this->sspComplex($this->products_warehouses_table, "products_warehouses.item_id", $product_warehouses_columns, $input, null, $where);
    }

    function getDTProductsForWarehouse($warehouse_id, $input)
    {
        $product_warehouses_columns = array(
            array('dt' => 0, 'db' => 'products_warehouses.item_id'),
            array('dt' => 1, 'db' => 'products.article'),
            array('dt' => 2, 'db' => 'CONCAT(\'<a href="/product?id=\', products.product_id, \'">\', IFNULL(products.name, \'no name\'), \'</a>\')'),
            array('dt' => 3, 'db' => 'CONCAT(\'<a href="/brand?id=\', brands.brand_id, \'">\', IFNULL(brands.name, \'no name\'), \'</a>\')'),
            array('dt' => 4, 'db' => 'products_warehouses.amount'),
            array('dt' => 5, 'db' => 'products.units'),
            array('dt' => 6, 'db' => 'products_warehouses.buy_price'),
            array('dt' => 7, 'db' => 'products_warehouses.buy_and_taxes'),
            array('dt' => 8, 'db' => 'products_warehouses.sell_price'),
            array('dt' => 9, 'db' => 'products_warehouses.dealer_price'),
            array('dt' => 10, 'db' => 'products_warehouses.total_price')
        );
        $this->sspComplex($this->products_warehouses_table, "products_warehouses.item_id", $product_warehouses_columns, $input, null, "products_warehouses.warehouse_id = $warehouse_id");
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

    function transferProductWarehouse($product_warehouse_id, $warehouse_id, $amount)
    {
//        $old_pw = $this->getFirst("SELECT *
//                FROM products_warehouses
//                WHERE product_warehouse_id = $product_warehouse_id");
//        $new_pw = $this->getFirst("SELECT *
//                FROM products_warehouses
//                WHERE product_id = ${old_pw['product_id']} AND warehouse_id = $warehouse_id");
//        if ($old_pw['amount'] == $amount) {
//            $this->delete("DELETE FROM products_warehouses
//                WHERE product_warehouse_id = $product_warehouse_id");
//        } else {
//            $old_amount = $old_pw['amount'] - $amount;
//            $this->update("UPDATE products_warehouses
//                SET amount = $old_amount
//                WHERE product_warehouse_id = $product_warehouse_id");
//        }
//        if ($new_pw != null) {
//            $old_amount = $old_pw['amount'];
//            $new_amount = $new_pw['amount'];
//            $new_new_amount = $new_pw['amount'] + $amount;
//            $buy_price = ($old_pw['buy_price'] * $amount + $new_pw['buy_price'] * $new_amount) / $new_new_amount;
//            $buy_and_taxes = ($old_pw['buy_and_taxes'] * $amount + $new_pw['buy_and_taxes'] * $new_amount) / $new_new_amount;
//            $sell_price = ($old_pw['sell_price'] * $amount + $new_pw['sell_price'] * $new_amount) / $new_new_amount;
//            $dealer_price = ($old_pw['dealer_price'] * $amount + $new_pw['dealer_price'] * $new_amount) / $new_new_amount;
//            $total_price = $new_pw['total_price'] + $old_pw['total_price'] * $amount / $old_amount;
//
//            $this->update("UPDATE products_warehouses
//                SET amount = $new_new_amount, buy_price = $buy_price, buy_and_taxes = $buy_and_taxes,
//                    sell_price = $sell_price, dealer_price = $dealer_price, total_price = $total_price
//                WHERE product_warehouse_id = ${new_pw['product_warehouse_id']}");
//        } else {
//            $this->insert("INSERT INTO products_warehouses (product_id, warehouse_id, amount, buy_price, buy_and_taxes, sell_price, dealer_price, total_price)
//                VALUES (${old_pw['product_id']}, $warehouse_id, $amount, ${old_pw['buy_price']}, ${old_pw['buy_and_taxes']},
//                 ${old_pw['sell_price']}, ${old_pw['dealer_price']}, ${old_pw['total_price']})");
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
}