<?php

include_once ('model_contractors.php');

class ModelContractor extends ModelContractors
{
    var $tableNames = ['table_contractor_payments', 'table_contractor_goods'];

    public $contractor_goods_where = '';
    public $contractor_goods_table = '';

    var $contractor_goods_columns_names = [
        '_item_id',
        'Order Id',
        'Category',
        'Date',
        'Direction',
        'Product Visual Name',
        'Quantity',
        'Units',
        'Sum',
        'Currency',
        'Sum in EUR',
    ];

    var $contractor_goods_columns = [
        array('dt' => 0, 'db' => "order_items.item_id"),
        array('dt' => 1, 'db' => "orders.order_id"),
    ];

    public function getInformation($money, $goods)
    {
        $totalMoney = 0;
        if (!empty($money)) {
            foreach ($money as $oneMoney) {
                $totalMoney += ($oneMoney[10] == 'Expense') ? -floatval($oneMoney[12]) : floatval($oneMoney[12]);
            }
        }

        $totalGoods = 0;
        if (!empty($goods)) {
            foreach ($goods as $good) {
                $totalGoods += ($good[4] == 'Expense') ? -floatval($good[10]) : floatval($good[10]);
            }
        }

        return [
            'money' => $totalMoney,
            'goods' => $totalGoods,
            'diff' => $totalGoods + $totalMoney
        ];

    }

    public function getContractorGoodsMovement($input, $printOpt)
    {
        $contractor_type = $input['products']['contractor_type'];
        $contractor_id = $input['products']['contractor_id'];

        $this->setSSPValues($contractor_id, $contractor_type);

        $ssp = [
            'columns' => $this->contractor_goods_columns,
            'columns_names' => $this->contractor_goods_columns_names,
            'db_table' => $this->contractor_goods_table,
            'page' => 'contractor',
            'table_name' => $this->tableNames[1],
            'primary' => 'order_items.item_id',
        ];

        if ($printOpt) {
            $printOpt['where'] = $this->contractor_goods_where;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $this->contractor_goods_where);
    }


    function getSelects($contractor_id, $contractor_type)
    {
        $this->setSSPValues($contractor_id, $contractor_type);
        $columns = $this->contractor_goods_columns;

        $ssp = $this->getSspComplexJson($this->contractor_goods_table, "order_items.item_id", $columns, null, null,
            $this->contractor_goods_where);

        $role = new Roles();

        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = [];
        $columnNames = $role->returnModelNames($this->contractor_goods_columns_names, 'contractor');

        if (!empty($rowValues)) {
            $selects = [];
            foreach ($rowValues as $product) {
                foreach ($product as $key => $value) {
                    if (!$value || $value == null)
                        continue;
                    $name = $columnNames[$key];
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

    public function getContractor($contractorId, $contractor_type)
    {
        $table = false;
        switch ($contractor_type) {
            case PAYMENT_CATEGORY_CLIENT:
                $table = 'clients';
                break;
            case PAYMENT_CATEGORY_SUPPLIER:
                $table = 'suppliers';
                break;
            case PAYMENT_CATEGORY_CUSTOMS:
                $table = 'customs';
                break;
            case PAYMENT_CATEGORY_OTHER:
                $table = 'other';
                break;
            case PAYMENT_CATEGORY_DELIVERY:
                $table = 'transportation_companies';
                break;
        }

        if ($table) {
            $primary = $this->getPrimaryKeyName($table);
            $where = "is_deleted = 0 AND `$primary` = $contractorId";
            $contractor = $this->getFirst("SELECT * FROM `$table` WHERE $where");
            if ($contractor) {
                unset($contractor[$primary]);
                $contractor['contractor_id'] = $contractorId;
                $contractor['contractor_type'] = $contractor_type;
            }
            return (!is_null($contractor)) ? $contractor : false;
        }
        return false;
    }

    public function getPaymentsColumnNames()
    {
        require_once dirname(__FILE__) . "/model_accountant.php";
        $modelAccountant = new ModelAccountant();
        return $modelAccountant->getColumns($modelAccountant->payments_column_names,
            'contractors', $this->tableNames[0], true);
    }

    private function setSSPValues($contractor_id, $contractor_type)
    {
        $where = ["(status_id = " . ISSUED . " OR status_id = " . RETURNED . ")"];
        $table = 'order_items LEFT JOIN products ON (order_items.product_id = products.product_id)';
        switch ($contractor_type) {
            case PAYMENT_CATEGORY_CLIENT:
                $table .= ' LEFT JOIN orders ON (order_items.manager_order_id = orders.order_id)';
                $where[] = "orders.client_id = $contractor_id";
                $this->contractor_goods_columns[] = array('dt' => 2,
                    'db' => "CONCAT(IF(order_items.status_id = '".ISSUED."', 'Client Issue', 'Client Return'))");
                $this->contractor_goods_columns[] = array('dt' => 3,
                    'db' => "CONCAT(IF(order_items.status_id = '".ISSUED."', order_items.issue_date, order_items.return_date))");
                $this->contractor_goods_columns[] = array('dt' => 4,
                    'db' => "CONCAT(IF(order_items.status_id = '".ISSUED."', 'Expense', 'Income'))");
                break;
            case PAYMENT_CATEGORY_SUPPLIER:
                $table .= ' LEFT JOIN suppliers_orders orders ON (order_items.supplier_order_id = orders.order_id)';
                $where[] = "orders.supplier_id = $contractor_id";
                $this->contractor_goods_columns[] = array('dt' => 2,
                    'db' => "CONCAT(IF(order_items.status_id = '".ISSUED."', 'Supplier Delivery', 'Client Return'))");
                $this->contractor_goods_columns[] = array('dt' => 3,
                    'db' => "CONCAT(IF(order_items.status_id = '".ISSUED."', order_items.issue_date, order_items.return_date))");
                $this->contractor_goods_columns[] = array('dt' => 4,
                    'db' => "CONCAT(IF(order_items.status_id = '".ISSUED."', 'Expense', 'Income'))");
                break;
        }

        $this->contractor_goods_table = $table;
        $this->contractor_goods_where = $where;

        $this->contractor_goods_columns[] = array('dt' => 5,
            'db' => "CONCAT('<a href=\"/product?id=', products.product_id, '\">', products.visual_name, '</a>')");
        $this->contractor_goods_columns[] = array('dt' => 6,
            'db' => "order_items.amount");
        $this->contractor_goods_columns[] = array('dt' => 7,
            'db' => "products.units");
        $this->contractor_goods_columns[] = array('dt' => 8,
            'db' => "IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount/100 as decimal(64, 2)), '')");
        $this->contractor_goods_columns[] = array('dt' => 9,
            'db' => "products.sell_price_currency");
        $this->contractor_goods_columns[] = array('dt' => 10,
            'db' => "IFNULL(CAST((order_items.sell_price * 
                (100 - order_items.discount_rate)) * order_items.amount/100 as decimal(64, 2)), '')");

        $this->contractor_goods_columns = $this->getColumns($this->contractor_goods_columns, 'contractor', $this->tableNames[1]);

    }

}