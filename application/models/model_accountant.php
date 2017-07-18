<?php

class ModelAccountant extends Model
{
    var $tableName = 'table_accountant';
    public $page;

    var $payments_columns = [
        array('dt' => 0, 'db' => "payments.payment_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/payment?id=', payments.payment_id, '\">', payments.payment_id, '</a>')"),
        array('dt' => 2, 'db' => "payments.date"),
        array('dt' => 3, 'db' => "entities.name"),
        array('dt' => 4, 'db' => "payments.category"),
        array('dt' => 5, 'db' => "CONCAT( 
            IF(payments.category = 'Client' OR payments.category = 'Comission Agent', 
                CONCAT('<a href=\"/client?id=', payments.contractor_id, '\">', clients.final_name, '</a>'), ''),
            IF(payments.category = 'Supplier', 
                CONCAT('<a href=\"/supplier?id=', payments.contractor_id, '\">', suppliers.name, '</a>'), ''),
            IF(payments.category = 'Customs', 
                CONCAT('<a href=\"/custom?id=', payments.contractor_id, '\">', customs.name, '</a>'), ''),
            IF(payments.category = 'Delivery', 
                CONCAT('<a href=\"/transportation?id=', payments.contractor_id, '\">', transport.name, '</a>'), ''))"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"/', IF(payments.category = 'Supplier', 
            'suppliers_order?id=', 'order?id='), payments.order_id, '\"\">', IFNULL(orders.visible_order_id, orders.order_id), 
            '</a>')"),
        array('dt' => 7, 'db' => "transfers.name"),
        array('dt' => 8, 'db' => "payments.currency"),
        array('dt' => 9, 'db' => "CAST(payments.sum as decimal(64, 2))"),
        array('dt' => 10, 'db' => "payments.direction"),
        array('dt' => 11, 'db' => "CAST(payments.currency_rate as decimal(64, 2))"),
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

    /**
     * @param string $type
     * @return array = ['columns', 'columns_names', 'db_table', 'table_name', 'primary', 'page']
     */
    function getSSPData($type = 'general')
    {

        $ssp = ['page' => $this->page];
        $where = ['payments.is_deleted = 0'];

        switch ($type) {
            case 'general':
            case 'contractor':
                $columns = $this->payments_columns;
                $ssp = array_merge($ssp, $this->getColumns($this->payments_column_names, $this->page,
                    $this->tableName, true));
                break;
            case 'monthly':
                $where[] = 'payments.is_monthly = 1';
                $columns = $this->getMonthlyPaymentsCols('columns');
                $ssp['columns_names'] = $this->getMonthlyPaymentsCols('name');
                $ssp['original_columns_names'] = $this->getMonthlyPaymentsCols('name', true);
        }

        $ssp = array_merge($ssp, $this->getColumns($columns, $this->page, $this->tableName));
        $ssp['db_table'] = $this->payments_table;
        $ssp['table_name'] = $this->tableName;
        $ssp['primary'] = 'payments.payment_id';

        if ($_SESSION['user']->role_id <= ROLE_SALES_MANAGER) {
            $this->unLinkStrings($columns, [5, 6]);
        }

        $ssp['where'] = $where;

        return $ssp;

    }

    function getDTPayments($input, $printOpt)
    {
        $type = isset($input['type']) && $input['type'] == 'monthly' ? 'monthly' : 'general';
        $ssp = $this->getSSPData($type);

        if (isset($input['products']) && isset($input['products']['contractor_id'])
            && isset($input['products']['contractor_type'])) {
            $ssp['where'][] = 'payments.contractor_id = ' . $input['products']['contractor_id'];
            $ssp['where'][] = "payments.category = '" . $input['products']['contractor_type'] . "'";
        }

        if ($printOpt) {
            $printOpt['where'] = $ssp['where'];
            echo $this->printTable($input, $ssp, $printOpt);
            return true;
        }

        $this->sspComplex($ssp['db_table'], $ssp['primary'], $ssp['columns'], $input, null, $ssp['where']);
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
                CONCAT('<a href=\"/client?id=', payments.contractor_id, '\">', clients.final_name, '</a>'), ''),
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

    public function getTableData($type = 'general', $opts = [])
    {
        $data = $this->getSSPData($type);
        $data['where'] = array_merge($data['where'], $opts);

        switch ($type) {
            case 'general':
                $cache = new Cache();
                $selects = $cache->getOrSet('payments', function() use ($data) {
                    return $this->getSelects($data);
                });
                break;
            case 'monthly':
                $selects = $this->getSelects($data);
                break;
            case 'contractor':
                $selects = $this->getSelects($data);
                break;
        }

        return array_merge($data, $selects);
    }

    function deletePayment($payment_id)
    {
        $this->update("UPDATE `payments`
                              SET `is_deleted` = 1 WHERE payment_id = $payment_id");
//        $this->delete("DELETE FROM payments WHERE payment_id = $payment_id");
        $this->updateOrderPayment($payment_id);

    }

    function getSelects($ssp, $where = [])
    {
        $ssp['where'] = array_merge($ssp['where'], $where);

        $sspJson = $this->getSspComplexJson($ssp['db_table'], $ssp['primary'],
            $ssp['original_columns'], null, null, $ssp['where']);
        $rowValues = json_decode($sspJson, true)['data'];
        $columnsNames = $ssp['original_columns_names'];
        $ignoreArray = ['_payment_id', 'Payment Id', 'Actions'];

        if (!empty($rowValues)) {
            $selects = Helper::getSelectsFromValues($rowValues, $columnsNames, $ignoreArray);
            return ['selectSearch' => $selects, 'filterSearchValues' => $rowValues];
        }
        return [];
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

        $brands = $this->getAssoc("SELECT * FROM brands WHERE is_deleted = 0");

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

    function getFinancialData()
    {



    }

    function checkMonthlyPayments()
    {
        $monthlyPayments = $this->getAssoc("SELECT * FROM payments WHERE is_deleted = 0 AND 
          is_monthly = 1 AND monthly_pay_day = CURDATE() AND is_monthly_active = 1 AND status = 'Not Executed'");
        if (!empty($monthlyPayments)) {
            foreach ($monthlyPayments as $monthlyPayment) {
                $paymentId = $monthlyPayment['payment_id'];
                unset($monthlyPayment['monthly_pay_day']);
                unset($monthlyPayment['is_monthly_active']);
                unset($monthlyPayment['is_monthly']);
                unset($monthlyPayment['payment_id']);
                $monthlyPayment['status'] = 'Not Executed';
                $names = join('`,`', array_keys($monthlyPayment));
                $values = join("','", $monthlyPayment);
                if ($names && $values) {
                    $insert = $this->insert("INSERT INTO payments (`$names`) VALUES ('$values')");
                    if ($insert) {
                        $this->update("UPDATE payments SET status = 'Executed' WHERE payment_id = $paymentId");
                    }
                }
            }
        }

        $monthlyPayments = $this->getAssoc("SELECT * FROM payments WHERE is_deleted = 0 AND 
          is_monthly = 1 AND monthly_pay_day <> CURDATE() AND is_monthly_active = 1 AND status = 'Executed'");
        if (!empty($monthlyPayments)) {
            foreach ($monthlyPayments as $monthlyPayment) {
                $paymentId = $monthlyPayment['payment_id'];
                $this->update("UPDATE payments SET status = 'Not Executed' WHERE payment_id = $paymentId");
            }
        }
    }

    function addPaymentFromFile($file)
    {

        $name = $file['name'];
        $error = $file['error'];
        $fileDir = $file['tmp_name'];

        if (!$name || $error)
            return false;

        $text = file_get_contents($fileDir);

        if ($text) {
            $text = iconv("windows-1251", "utf-8", $text);

            $date = '';
            preg_match_all('|СекцияРасчСчет(.+)КонецРасчСчет|isU', $text, $checkingAcc);
            if ($checkingAcc && isset($checkingAcc[1]) && !empty($checkingAcc[1])) {
                foreach ($checkingAcc[1] as $oneCheckingAcc) {
                    $oneCheckingAcc = explode("\n", $oneCheckingAcc);

                    if (!empty($oneCheckingAcc)) {
                        foreach ($oneCheckingAcc as $item) {
                            $tempArr = explode('=', $item);
                            $name = Helper::arrGetVal($tempArr, 0);
                            $value = Helper::arrGetVal($tempArr, 1);
                            if ($name == 'ДатаНачала') {
                                $date = (string) date('Y-m-d', strtotime($value));
                            }

                        }
                    }
                }

            }

            preg_match_all('|СекцияДокумент(.+)КонецДокумента|isU', $text, $arr);
            if ($arr && isset($arr[1]) && !empty($arr[1])) {
                foreach ($arr[1] as $onePayment)
                {
                    $onePayment = explode("\n", $onePayment);

                    if (!empty($onePayment)) {
                        $data = [];
                        $receiver = [];
                        $sender = [];
                        foreach ($onePayment as $values) {

                            $tempArr = explode('=', $values);
                            $name = Helper::arrGetVal($tempArr, 0);
                            $value = Helper::arrGetVal($tempArr, 1);
                            $value = trim($value);

                            if (!$name || !$value)
                                continue;

                            switch ($name) {
                                case 'Сумма':
                                    $value = (double) $value;
                                    $data['sum'] = $value;
                                    require_once dirname(__FILE__) . "/model_payment.php";
                                    $data['currency'] = 'РУБ';
                                    $modelPayment = new ModelPayment();
                                    $currency = (float) $modelPayment->getOfficialCurrency('РУБ');
                                    $data['currency_rate'] = $currency;
                                    $data['sum_in_eur'] = (double) ($value * $currency);
                                    break;
                                case 'НазначениеПлатежа':
                                    $data['purpose_of_payment'] = Helper::safeVar($value);
                                    break;
                                case 'ПлательщикИНН':
                                    $sender['inn'] = $value;
                                    break;
                                case 'ПолучательИНН':
                                    $receiver['inn'] = $value;
                                    break;
                                case 'Получатель':
                                    $receiver['name'] = Helper::safeVar($value);
                                    break;
                                case 'Плательщик':
                                    $sender['name'] = Helper::safeVar($value);
                                    break;
                            }
                        }

                        if (!empty($data)) {

//                            $data['date'] = (string) date('Y-m-d');
                            $data['date'] = $date;
                            $data['responsible_person_id'] = $this->user->user_id;
                            $data['status'] = 'Not Executed';
                            $data['category'] = PAYMENT_CATEGORY_OTHER;
                            $data['legal_entity_id'] = 2;
                            $data['transfer_type_id'] = 2;

                            $contractor = [];

                            switch (AVENA_INN) {
                                case Helper::arrGetVal($receiver, 'inn'):
                                    $data['direction'] = PAYMENT_DIRECTION_INCOME;
                                    $contractor = $sender;
                                    break;
                                case Helper::arrGetVal($sender, 'inn'):
                                    $data['direction'] = PAYMENT_DIRECTION_EXPENSE;
                                    $contractor = $receiver;
                                    break;
                            }

                            if (!empty($contractor)) {
                                $existingContractor = $this->getFirst("SELECT * FROM other WHERE inn = ${contractor['inn']}");
                                if ($existingContractor) {
                                    $data['contractor_id'] = $existingContractor['other_id'];
                                } else {
                                    $namesC = join('`, `', array_keys($contractor));
                                    $valuesC = join("', '", $contractor);
                                    $data['contractor_id'] = $this->insert("INSERT INTO other (`$namesC`) VALUES ('$valuesC')");
                                }
                            }

                            $names = join('`, `', array_keys($data));
                            $values = join("', '", $data);
                            $this->insert("INSERT INTO payments (`$names`) VALUES ('$values')");

                        }

                    }

                }
            }
        }
    }

    function getBalanceData($dateBegin = null, $dateEnd = null)
    {

        $dateBegin = (is_null($dateBegin) || !$dateBegin) ? $this->getFirst("SELECT date FROM payments 
          WHERE is_deleted = 0 AND is_monthly = 0 AND date IS NOT NULL")['date'] : $dateBegin;
        $dateEnd = (is_null($dateEnd) || !$dateEnd) ? date('Y-m-d') : $dateEnd;

        $payments = $this->getAssoc("SELECT * FROM payments WHERE is_deleted = 0 AND is_monthly = 0");
        $data = [
            1 => [],
            2 => []
        ];
        $dataBalance = [
            'Balance in the beginning' => 0,
            'Sum plus for period' => 0,
            'Sum minus for period' => 0,
            'Saldo' => 0,
            'Balance in the end' => 0
        ];
        if (!empty($payments)) {
            foreach ($payments as $payment) {

                $currency = $payment['currency'];
                $date = $payment['date'];
                $direction = $payment['direction'];
                $sum = floatval($payment['sum']);
                $transferType = +$payment['transfer_type_id'];
                $sumWithDir = ($direction == 'Expense') ? -$sum : +$sum;

                if (!isset($data[$transferType][$currency])) {
                    $data[$transferType][$currency] = $dataBalance;
                }

                $localDataBalance = $data[$transferType][$currency];

                if (strtotime($date) < strtotime($dateBegin)) {
                    $localDataBalance['Balance in the beginning'] += $sumWithDir;
                } elseif (strtotime($date) >= strtotime($dateBegin) && strtotime($date) <= strtotime($dateEnd)) {
                    if ($direction === 'Income') {
                        $localDataBalance['Sum plus for period'] += $sum;
                        $localDataBalance['Saldo'] += $sum;
                    } else {
                        $localDataBalance['Sum minus for period'] += $sum;
                        $localDataBalance['Saldo'] -= $sum;
                    }
                }

                $localDataBalance['Balance in the end'] = $localDataBalance['Balance in the beginning'] +
                    $localDataBalance['Saldo'];

                $data[$transferType][$currency] = $localDataBalance;

            }
        }

        return $data;

    }

}