<?php

class ModelAccountant extends Model
{
    var $tableName = 'table_accountant';

    var $payments_columns = [
        array('dt' => 0, 'db' => "payments.payment_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/payment?id=', payments.payment_id, '\">', payments.payment_id, '</a>')"),
        array('dt' => 2, 'db' => "payments.date"),
        array('dt' => 3, 'db' => "entities.name"),
        array('dt' => 4, 'db' => "payments.category"),
        array('dt' => 5, 'db' => "CONCAT( 
            IF(payments.category = 'Client' OR payments.category = 'Comission Agent', 
                CONCAT('<a href=\"/client?id=', payments.contractor_id, '\">', clients.name, '</a>'), ''),
            IF(payments.category = 'Supplier', 
                CONCAT('<a href=\"/supplier?id=', payments.contractor_id, '\">', suppliers.name, '</a>'), ''),
            IF(payments.category = 'Customs', 
                CONCAT('<a href=\"/custom?id=', payments.contractor_id, '\">', customs.name, '</a>'), ''),
            IF(payments.category = 'Delivery', 
                CONCAT('<a href=\"/transportation?id=', payments.contractor_id, '\">', transport.name, '</a>'), ''))"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"/', IF(payments.category = 'Supplier', 
            'suppliers_order?id=', 'order?id='), payments.order_id, '\"\">', orders.visible_order_id, 
            '</a>')"),
        array('dt' => 7, 'db' => "transfers.name"),
        array('dt' => 8, 'db' => "payments.currency"),
        array('dt' => 9, 'db' => "CAST(payments.sum as decimal(64, 2))"),
        array('dt' => 10, 'db' => "payments.direction"),
        array('dt' => 11, 'db' => "payments.currency_rate"),
        array('dt' => 12, 'db' => "CAST(payments.sum_in_eur as decimal(64, 2))"),
        array('dt' => 13, 'db' => "payments.purpose_of_payment"),
        array('dt' => 14, 'db' => "CONCAT(users.first_name, ' ', users.last_name)"),
        array('dt' => 15, 'db' => "article_of_expense.name"),
        array('dt' => 16, 'db' => "category_of_expense.name"),
        array('dt' => 17, 'db' => "category_of_expense.expense_keyfigure"),
        array('dt' => 18, 'db' => "CONCAT('<span class=\"label label-', IF(payments.status = 'Executed', 
            'success', 'default'), '\">', payments.status, '</span>')"),
        array('dt' => 19, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a data-toggle=\"confirmation\" data-title=\"Are you sure to delete the payment?\"
                                   href=\"/accountant/delete?payment_id=', payments.payment_id, '\"
                                   class=\"table-confirm-btn\" data-placement=\"left\" data-popout=\"true\" 
                                   data-singleton=\"true\">
                                       <span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span>
                                   </a>'),
                '</div>')")
    ];

    var $payments_column_names = [
        '_payment_id',
        'Payment Id',
        'Payment Date',
        'Legal entity',
        'Category',
        'Contractor',
        'Order Id',
        'Transfer Type',
        'Currency',
        'Sum',
        'Direction',
        'Currency Rate',
        'Sum in EURO',
        'Purpose of payment',
        'Responsible Person',
        'Article of expense',
        'Category of expense',
        'Expense keyfigures',
        'Status',
        'Actions'
    ];


    var $payments_table = "payments
                            left join legal_entities as entities on entities.legal_entity_id = payments.legal_entity_id
                            left join transfers on transfers.transfer_id = payments.transfer_type_id
                            left join users on users.user_id = payments.responsible_person_id
                            left join article_of_expense on payments.expense_article_id = article_of_expense.article_id
                            left join category_of_expense on article_of_expense.category_id = category_of_expense.category_id
                            left join clients on payments.contractor_id = clients.client_id
                            left join suppliers on payments.contractor_id = suppliers.supplier_id
                            left join customs on payments.contractor_id = customs.custom_id
                            left join transportation_companies as transport on 
                                payments.contractor_id = transport.transportation_company_id
                            left join orders ON (payments.order_id = orders.order_id AND payments.category = 'Client')
                            ";

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTPayments($input, $printOpt, $where = [])
    {
        $columns = $this->payments_columns;
        if (isset($input['type']) && $input['type'] == 'monthly') {
            $where[] = 'payments.is_monthly = 1';
            $columns = $this->getMonthlyPaymentsCols('columns');
        }
        if ($this->user->permissions <= SALES_MANAGER_PERM) {
            $where[] = 'payments.is_deleted = 0';
            $this->unLinkStrings($columns, [5, 6]);
        }
        if (isset($input['products']) && isset($input['products']['contractor_id'])
            && isset($input['products']['contractor_type'])) {
            $where[] = 'payments.contractor_id = ' . $input['products']['contractor_id'];
            $where[] = "payments.category = '" . $input['products']['contractor_type'] . "'";
        }

        $ssp = [
            'columns' => $columns,
            'columns_names' => $this->payments_column_names,
            'db_table' => $this->payments_table,
            'page' => 'accountant',
            'table_name' => $this->tableName,
            'primary' => 'payments.payment_id',
        ];

        if ($printOpt) {
            $printOpt['where'] = $where;
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $where);
    }

    function getDTOrderPayments($order_id, $type, $input)
    {
        $columns = [
            array('dt' => 0, 'db' => "payments.payment_id"),
            array('dt' => 1, 'db' => "CONCAT('<a href=\"/payment?id=', payments.payment_id, '\">', payments.payment_id, '</a>')"),
            array('dt' => 2, 'db' => "payments.date"),
            array('dt' => 3, 'db' => "entities.name"),
            array('dt' => 4, 'db' => "payments.category"),
            array('dt' => 5, 'db' => "CONCAT( 
            IF(payments.category = 'Client' OR payments.category = 'Comission Agent', 
                CONCAT('<a href=\"/client?id=', payments.contractor_id, '\">', clients.name, '</a>'), ''),
            IF(payments.category = 'Supplier', 
                CONCAT('<a href=\"/supplier?id=', payments.contractor_id, '\">', suppliers.name, '</a>'), ''),
            IF(payments.category = 'Customs', 
                CONCAT('<a href=\"/custom?id=', payments.contractor_id, '\">', customs.name, '</a>'), ''),
            IF(payments.category = 'Delivery', 
                CONCAT('<a href=\"/transportation?id=', payments.contractor_id, '\">', transport.name, '</a>'), ''))"),
            array('dt' => 6, 'db' => "orders.visible_order_id"),
            array('dt' => 7, 'db' => "transfers.name"),
            array('dt' => 8, 'db' => "CAST(payments.sum as decimal(64, 2))"),
            array('dt' => 9, 'db' => "payments.currency"),
            array('dt' => 10, 'db' => "payments.direction"),
            array('dt' => 11, 'db' => "payments.currency_rate"),
            array('dt' => 12, 'db' => "CAST(payments.sum_in_eur as decimal(64, 2))"),
            array('dt' => 13, 'db' => "payments.purpose_of_payment"),
            array('dt' => 14, 'db' => "article_of_expense.name"),
            array('dt' => 15, 'db' => "category_of_expense.name"),
            array('dt' => 16, 'db' => "CONCAT(users.first_name, ' ', users.last_name)"),
            array('dt' => 17, 'db' => "CONCAT('<span class=\"label label-', IF(payments.status = 'Executed', 
            'success', 'default'), '\">', payments.status, '</span>')"),
        ];

        if ($_SESSION['perm'] <= SALES_MANAGER_PERM) {
            $this->unLinkStrings($columns, [1, 5]);
        }

        $where = "payments.order_id = $order_id AND category = '$type' AND payments.is_deleted = 0";

        return $this->sspComplex($this->payments_table, "payments.payment_id", $columns,
            $input, null, $where);
    }

    function deletePayment($payment_id)
    {
        $this->update("UPDATE `payments`
                              SET `is_deleted` = 1 WHERE payment_id = $payment_id");
//        $this->delete("DELETE FROM payments WHERE payment_id = $payment_id");
        $this->updateOrderPayment($payment_id);

    }

    function getSelects($isMonthly = false, $where = [])
    {
        $columns = $this->payments_columns;
        $role = new Roles();
        $where[] = 'payments.is_deleted = 0';
        if ($isMonthly) {
            $where[] = 'payments.is_monthly = 1';
            $cols = $this->getMonthlyPaymentsCols('columns');
            $columnNames = $this->getMonthlyPaymentsCols('name');
        } else {
            $cols = $role->returnModelColumns($columns, 'accountant');
            $columnNames = $role->returnModelNames($this->payments_column_names, 'accountant');
        }

        if ($_SESSION['perm'] <= SALES_MANAGER_PERM) {
            $this->unLinkStrings($cols, [5, 6]);
        }

        $ssp = $this->getSspComplexJson($this->payments_table, "payments.payment_id", $cols, null, null,
            $where);

        $rowValues = json_decode($ssp, true)['data'];
        $ignoreArray = ['_payment_id', 'Payment Id', 'Actions'];

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

    public function getMonthlyPaymentsCols($type, $isOriginal = false)
    {

        switch ($type) {
            case 'name':
                array_splice($this->payments_column_names, 18, 0, 'Pay Day');
                array_splice($this->payments_column_names, 18, 0, 'Monthly Status');
                if (!$isOriginal) {
                    return $this->getColumns($this->payments_column_names,
                        'accountant', $this->tableName . 'monthly', true);
                }
                $roles = new Roles();
                return $roles->returnModelNames($this->payments_column_names, 'accountant');
            case 'columns':
                $actions = $this->payments_columns[19];
                $this->payments_columns[19] = array('dt' => 19, 'db' => "payments.monthly_pay_day");
                $this->payments_columns[] = array('dt' => 20, 'db' => "IF(payments.is_monthly_active, 'Active', 'Not Active')");
                $actions['dt'] = 21;
                $this->payments_columns[] = $actions;
                return $this->payments_columns;
        }

    }

    public function initCatalogueParser($array, $clear = false)
    {

        if ($clear) {
            $this->clearTable('products');
            $this->clearIncrement('products');
            $this->clearIncrement('nls_products');
        }

        $brands = $this->getAssoc("SELECT * FROM brands");

        function getBrandId($brandStr, $brands) {
            foreach ($brands as $brand) {
                if ($brand['name'] == $brandStr)
                    return $brand['brand_id'];
            }
            return null;
        }

        foreach ($array as $item) {


            $brand = $item['brand']['val'];
            if ($brand !== null) {
                $item['brand_id'] = [
                    'val' => getBrandId($brand, $brands),
                    'type' => 'int'
                ];
            }
            unset($item['brand']);


            $names = '';
            $rusNames = '';
            $values = '';
            $rusValues = '';
            $empty = count($item) - 1;

            foreach ($item as $name => $valsArray) {
                $value = trim($valsArray['val']);
                $type = $valsArray['type'];
                if (!$value || $value == null) {
                    $empty--;
                    continue;
                }
                $value = mysql_real_escape_string($value);

                if (strpos($name, '_rus') !== false) {
                    $name = str_replace('_rus', '', $name);
                    $rusNames .= $name . ', ';
                    $rusValues .= "'$value', ";
                    continue;
                }

                $names .= $name . ', ';
                if ($type == 'string')
                    $values .= "'$value', ";
                else {
                    if ($type == 'float' || $type == 'double') {
                        $value = floatval($value);
                    }
                    if ($type == 'int') {
                        $value = intval($value);
                    }
                    $values .= "$value, ";
                }
            }

            if ($empty <= 0)
                continue;

            $productId = $this->insert("INSERT INTO products ($names status)
                          VALUES ($values 0)");

            if ($productId && $rusNames && $rusValues) {
                $this->insert("INSERT INTO nls_products ($rusNames product_id, language_id)
                          VALUES ($rusValues $productId, 2)");
            }

        }
        echo 'Job is Done, my Master!';
        die();
    }

    // parser fo expenses
    function initParser($rows)
    {
//
//        $expense = [];
//        foreach ($rows as $key => $value) {
//
//            $articleEn = mysql_real_escape_string($value[1]);
//            $articleRus = mysql_real_escape_string($value[0]);
//            $categoryEn = mysql_real_escape_string($value[3]);
//            $categoryRus = mysql_real_escape_string($value[2]);
//            $keyfigure = mysql_real_escape_string($value[4]);
//
//            $item = ['value' => $articleEn, 'rus' => $articleRus];
//
//            if (isset($expense[$categoryEn])) {
//                $expense[$categoryEn]['values'][] = $item;
//            } else {
//                $expense[$categoryEn] = [
//                    'rus' => $categoryRus,
//                    'keyfigure' => $keyfigure,
//                    'values' => [$item]
//                ];
//            }
//        }
//
//        foreach ($expense as $categoryName => $categoryArr) {
//            $categoryEn = $categoryName;
//            $categoryRus = $categoryArr['rus'];
//            $articleAr = $categoryArr['values'];
//            $keyfigure = $categoryArr['keyfigure'];
//
//            $categoryId = $this->insert("INSERT INTO category_of_expense (name, expense_keyfigure)
//                                            VALUES ('$categoryEn', '$keyfigure')");
//
//            if ($categoryId) {
//                $maxNls = $this->getMax("SELECT MAX(resource_id) FROM nls_resources") + 1;
//                $nlsId = $this->insert("INSERT INTO nls_resources (nls_resource_id, language_id, value)
//                                            VALUES ($maxNls, 2, '$categoryRus')");
//                if ($nlsId) {
//                    $this->update("UPDATE category_of_expense SET `nls_resource_id` = $nlsId WHERE category_id = $categoryId");
//                }
//
//                foreach ($articleAr as $key => $valueItem) {
//                    $articleEn = $valueItem['value'];
//                    $articleRus = $valueItem['rus'];
//
//                    $articleId = $this->insert("INSERT INTO article_of_expense (name, category_id, nls_resource_id)
//                                            VALUES ('$articleEn', $categoryId, 0)");
//                    if ($articleId) {
//                        $maxNls = $this->getMax("SELECT MAX(resource_id) FROM nls_resources") + 1;
//                        $nlsId = $this->insert("INSERT INTO nls_resources (nls_resource_id, language_id, value)
//                                            VALUES ($maxNls, 2, '$articleRus')");
//                        if ($nlsId) {
//                            $this->update("UPDATE article_of_expense SET `nls_resource_id` = $nlsId WHERE article_id = $articleId");
//                        }
//                    }
//                }
//            }
//
//        }

    }

}