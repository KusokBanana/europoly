<?php

include_once ('model_contractors.php');

class ModelContractor extends ModelContractors
{
    var $tableNames = ['table_contractor_payments', 'table_contractor_goods', 'table_contractor_services'];
    public $page;

    public $contractor_goods_where = [];
    public $contractor_goods_table = '';
    public $contractor_services_where = '';
    public $contractor_services_table = '';
    public $contractor_services_primary = '';
    public $contractor_goods_primary = 'order_items.item_id';

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

    var $contractor_services_columns_names = [
        '_item_id',
        'Type',
        'Name',
        'Direction',
        'Sum',
        'Currency',
        'Sum in EUR',
    ];

    var $goods_search_types = [PAYMENT_CATEGORY_SUPPLIER, PAYMENT_CATEGORY_CLIENT];
    var $services_search_types = [PAYMENT_CATEGORY_CLIENT, PAYMENT_CATEGORY_DELIVERY,
        PAYMENT_CATEGORY_CUSTOMS, PAYMENT_CATEGORY_OTHER, PAYMENT_CATEGORY_SUPPLIER, PAYMENT_CATEGORY_COMMISSION_AGENT];

    public function isGoodsSearch($contractor_type)
    {
        return in_array($contractor_type, $this->goods_search_types);
    }

    public function isServicesSearch($contractor_type)
    {
        return in_array($contractor_type, $this->services_search_types);
    }

    var $contractor_goods_columns = [
        array('dt' => 0, 'db' => "order_items.item_id"),
        array('dt' => 1, 'db' => "IFNULL(orders.visible_order_id, orders.order_id)"),
    ];
    var $contractor_services_columns = [];

    public function getInformation($money, $goods, $services, $contractorType)
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

        $totalServices = 0;
        if (!empty($services)) {
            foreach ($services as $service) {
                $totalServices += ($service[3] == 'Expense') ? -floatval($service[6]) : floatval($service[6]);
            }
        }

        return [
            'money' => round($totalMoney, 2),
            'goods' => round($totalGoods, 2),
            'services' => round($totalServices, 2),
            'diff' => round($totalGoods + $totalMoney + $totalServices, 2)
        ];

    }

    public function getContractorGoodsMovement($input, $printOpt)
    {
        $contractor_type = $input['products']['contractor_type'];
        $contractor_id = $input['products']['contractor_id'];

        $this->setSSPGoodsValues($contractor_id, $contractor_type);

        $ssp = [
            'columns' => $this->contractor_goods_columns,
            'columns_names' => $this->contractor_goods_columns_names,
            'db_table' => $this->contractor_goods_table,
            'page' => 'contractor',
            'table_name' => $this->tableNames[1],
            'primary' => $this->contractor_goods_primary,
            'where' => $this->contractor_goods_where,
        ];

        if ($printOpt) {
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $ssp['where']);
    }

    public function getContractorServices($input, $printOpt)
    {
        $contractor_type = $input['products']['contractor_type'];
        $contractor_id = $input['products']['contractor_id'];

        $this->setSSPServicesValues($contractor_id, $contractor_type);

        $ssp = [
            'columns' => $this->contractor_services_columns,
            'columns_names' => $this->contractor_services_columns_names,
            'db_table' => $this->contractor_services_table,
            'page' => 'contractor',
            'table_name' => $this->tableNames[2],
            'primary' => $this->contractor_services_primary,
            'where' => $this->contractor_services_where,
        ];

        if ($printOpt) {
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $dynamicData = json_encode(['recordsFiltered' => 0, 'recordsTotal' => 0, 'data' => [], 'draw' => 1]);

        if ($contractor_type !== PAYMENT_CATEGORY_OTHER && $contractor_type !== PAYMENT_CATEGORY_SUPPLIER) {
            $dynamicData = $this->getSspComplexJson($ssp['db_table'], $ssp['primary'], $ssp['columns'],
                $input, null, $ssp['where']);
        }

        echo $this->mergeWithStaticServices($contractor_type, $contractor_id, $dynamicData);

    }

    function mergeWithStaticServices($contractor_type, $contractor_id, $jsonData)
    {

        $staticServices = $this->getAssoc("SELECT 
          item_id as '0', 
          'Other Services' as '1', 
          name as '2', 
          direction as '3', 
          sum as '4', 
          currency as '5', 
          sum as '6' 
          FROM services_items WHERE contractor_type = '$contractor_type' AND contractor_id = $contractor_id");

        $data = json_decode($jsonData, true);

        $count = count($staticServices);

        $data['draw'] = (count($data['data']) && $count) ? 1 : 0;
        if ($data['draw'] == 0) {
            $data['data'] = [];
        }

        $data['recordsTotal'] += $count;
        $data['recordsFiltered'] += $count;
        $data['data'] = array_merge($data['data'], $staticServices);

        return json_encode($data);

    }

    function getSelects($contractor_id, $contractor_type, $type)
    {
        if ($type == 'goods')
            $this->setSSPGoodsValues($contractor_id, $contractor_type);
        else
            $this->setSSPServicesValues($contractor_id, $contractor_type);

        $columns = $this->{'contractor_'.$type.'_columns'};
        $table = $this->{'contractor_'.$type.'_table'};
        $where = $this->{'contractor_'.$type.'_where'};
        $colNames = $this->{'contractor_'.$type.'_columns_names'};
        $primary = $this->{'contractor_'.$type.'_primary'};

        $dynamicData = json_encode(['recordsFiltered' => 0, 'recordsTotal' => 0, 'data' => [], 'draw' => 1]);

        if ($type == 'services') {
            if ($contractor_type !== PAYMENT_CATEGORY_OTHER && $contractor_type !== PAYMENT_CATEGORY_SUPPLIER) {
                $dynamicData = $this->getSspComplexJson($table, $primary, $columns,
                    null, null, $where);
            }
            $ssp = $this->mergeWithStaticServices($contractor_type, $contractor_id, $dynamicData);
        } else {
            $ssp = $this->getSspComplexJson($table, $primary, $columns, null, null,
                $where);
        }

        $role = new Roles();

        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = [];
        $columnsNames = $role->returnModelNames($colNames, 'contractor');

        if (!empty($rowValues)) {
            $selects = Helper::getSelectsFromValues($rowValues, $columnsNames, $ignoreArray);
            return ['selectSearch' => $selects, 'filterSearchValues' => $rowValues];
        }
        return [];
    }

    public function setSSPServicesValues($contractor_id, $contractor_type)
    {

        switch ($contractor_type) {
            case PAYMENT_CATEGORY_CLIENT:
                $this->contractor_services_table = 'orders ';
                $this->contractor_services_table .= 'LEFT JOIN order_items ON (order_items.manager_order_id = orders.order_id) '.
                 'LEFT JOIN products ON (order_items.product_id = products.product_id) '.
                 'LEFT JOIN clients ON (clients.client_id = orders.client_id)';

                $this->contractor_services_where = ["order_items.status_id = " . ISSUED];
                $this->contractor_services_where[] = "orders.client_id = $contractor_id";
                $this->contractor_services_where[] = "order_items.manager_order_id IS NOT NULL";

                $this->contractor_services_columns[] = array('dt' => 0,
                    'db' => "IFNULL(orders.visible_order_id, orders.order_id)");
                $this->contractor_services_columns[] = array('dt' => 1,
                    'db' => "'Comission Agent Bonus'");
                $this->contractor_services_columns[] = array('dt' => 2,
                    'db' => "CONCAT('Bonus for Order <a href=\"/order?id=', orders.order_id, '\">', 
                        IFNULL(orders.visible_order_id, orders.order_id), '</a> for Client <a href=\"/client?id=', clients.client_id, '\">', 
                        clients.final_name, '</a>')");
                $this->contractor_services_columns[] = array('dt' => 3,
                    'db' => "'Income'");
                $this->contractor_services_columns[] = array('dt' => 4,
                    'db' => "SUM(order_items.commission_agent_bonus)");
                $this->contractor_services_columns[] = array('dt' => 5,
                    'db' => "products.sell_price_currency");
                $this->contractor_services_columns[] = array('dt' => 6,
                    'db' => "SUM(order_items.commission_agent_bonus)");

                $this->contractor_services_primary = 'orders.order_id';
                break;
            case PAYMENT_CATEGORY_DELIVERY:
                $this->contractor_services_table = 'trucks ';
                $this->contractor_services_table .= 'LEFT JOIN order_items ON (order_items.truck_id = trucks.id)'.
                ' LEFT JOIN products ON (order_items.product_id = products.product_id)';

                $this->contractor_services_where = ["order_items.status_id >= " . ON_STOCK];
                $this->contractor_services_where[] = "trucks.transportation_company_id = $contractor_id";

                $this->contractor_services_columns[] = array('dt' => 0,
                    'db' => "trucks.id");
                $this->contractor_services_columns[] = array('dt' => 1,
                    'db' => "'Goods Delivery'");
                $this->contractor_services_columns[] = array('dt' => 2,
                    'db' => "CONCAT('Delivery of goods for Truck <a href=\"/truck?id=', trucks.id, '\">', 
                        trucks.id, '</a>')");
                $this->contractor_services_columns[] = array('dt' => 3,
                    'db' => "'Income'");
                $this->contractor_services_columns[] = array('dt' => 4,
                    'db' => "SUM(order_items.delivery_price)");
                $this->contractor_services_columns[] = array('dt' => 5,
                    'db' => "products.sell_price_currency");
                $this->contractor_services_columns[] = array('dt' => 6,
                    'db' => "SUM(order_items.delivery_price)");

                $this->contractor_services_primary = 'trucks.id';
                break;
            case PAYMENT_CATEGORY_CUSTOMS:
                $this->contractor_services_table = 'trucks ';
                $this->contractor_services_table .= 'LEFT JOIN order_items ON (order_items.truck_id = trucks.id)'.
                    ' LEFT JOIN products ON (order_items.product_id = products.product_id)';

                $this->contractor_services_where = ["order_items.status_id >= " . ON_STOCK];
                $this->contractor_services_where[] = "trucks.custom_id = $contractor_id";

                $this->contractor_services_columns[] = array('dt' => 0,
                    'db' => "trucks.id");
                $this->contractor_services_columns[] = array('dt' => 1,
                    'db' => "'Customs Clearing'");
                $this->contractor_services_columns[] = array('dt' => 2,
                    'db' => "CONCAT('Customs clearing for Truck <a href=\"/truck?id=', trucks.id, '\">', 
                        trucks.id, '</a>')");
                $this->contractor_services_columns[] = array('dt' => 3,
                    'db' => "'Income'");
                $this->contractor_services_columns[] = array('dt' => 4,
                    'db' => "IFNULL(SUM(order_items.import_VAT + order_items.import_brokers_price + order_items.import_tax), 0)");
                $this->contractor_services_columns[] = array('dt' => 5,
                    'db' => "products.sell_price_currency");
                $this->contractor_services_columns[] = array('dt' => 6,
                    'db' => "IFNULL(SUM(order_items.import_VAT + order_items.import_brokers_price + order_items.import_tax), 0)");

                $this->contractor_services_primary = 'trucks.id';
                break;
        }

        $colsArray = $this->getColumns($this->contractor_services_columns,
            'contractor', $this->tableNames[2]);
        $this->contractor_services_columns = $colsArray['columns'];

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
        $colsArray = $modelAccountant->getColumns($modelAccountant->payments_column_names,
        'contractors', $this->tableNames[0], true);
        return $colsArray['columns_names'];
    }

    private function setSSPGoodsValues($contractor_id, $contractor_type)
    {
        $where = ["(order_items.status_id = " . ISSUED . " OR order_items.status_id = " . RETURNED . ")"];
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

        $colsArray = $this->getColumns($this->contractor_goods_columns, 'contractor', $this->tableNames[1]);
        $this->contractor_goods_columns = $colsArray['columns'];

    }

    public function addServicesItem($formData)
    {
        foreach ($formData as $field => $value) {
            $formData[$field] = trim($value);
            if (!$value)
                return false;

            $formData[$field] = Helper::safeVar($value);
        }

        $names = join('`,`', array_keys($formData));
        $vals = join("','", $formData);

        $this->insert("INSERT INTO services_items (`$names`) VALUES ('$vals')");

    }

}