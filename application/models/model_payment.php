<?php

class ModelPayment extends Model
{

    public function __construct()
    {
        $this->connect_db();
    }


    function getLegalEntitiesIdName()
    {
        return $this->getAssoc("SELECT 	legal_entity_id, name FROM legal_entities");
    }
    function getTransferTypesIdName()
    {
        return $this->getAssoc("SELECT transfer_id, name FROM transfers");
    }

    function getSelectByCategory($category, $contractorId = false)
    {
        switch ($category) {
            case 'Client':
                $select = (!$contractorId) ? $this->getClientsByType() :
                    $this->getOrdersBy($contractorId, 'client_id');
                break;
            case 'Comission Agent':
                $select = (!$contractorId) ? $this->getClientsByType(COMISSION_AGENT) :
                    $this->getOrdersBy($contractorId, 'commission_agent_id');
                break;
            case 'Supplier':
                $select = (!$contractorId) ? $this->getBrands() :
                    $this->getOrdersBy($contractorId, 'supplier_id');
                break;
            case 'Customs':
                $select = (!$contractorId) ? $this->getCustoms() :
                    false;
                break;
            case 'Delivery':
                $select = (!$contractorId) ? $this->getDeliveries() :
                    false;
                break;
            case 'Other':
                $select = (!$contractorId) ? $this->getOthers() :
                    false;
                break;
        }
        return isset($select) ? $select : false;
    }


    public function getClientsByType($type = '')
    {
        $where = $type ? "`type` = '$type' AND " : '';
        $where .= "`is_deleted` = 0";
        return $this->getAssoc("SELECT client_id as id, name FROM clients WHERE $where");
    }

    public function getBrands()
    {
        return $this->getAssoc("SELECT supplier_id as id, name FROM suppliers WHERE is_deleted = 0");
    }

    public function getCustoms()
    {
        return $this->getAssoc("SELECT custom_id as id, name FROM customs WHERE is_deleted = 0");
    }
    public function getDeliveries()
    {
        return $this->getAssoc("SELECT transportation_company_id as id, name FROM transportation_companies 
                                    WHERE is_deleted = 0");
    }
    public function getOthers()
    {
        return $this->getAssoc("SELECT other_id as id, name FROM other 
                                    WHERE is_deleted = 0");
    }

    public function getOrdersBy($clientId, $by)
    {
        $ordersTable = ($by == 'supplier_id') ? 'suppliers_orders' : 'orders';
        $dateField = ($by == 'supplier_id') ? 'supplier_date_of_order' : 'start_date';
        $idField = ($by == 'supplier_id') ? 'order_id' : 'visible_order_id';
        $return = $this->getAssoc("SELECT DISTINCT $ordersTable.order_id as id, 
                  CONCAT($idField, ', ', $dateField) as name 
                  FROM $ordersTable
                  WHERE $by = $clientId");
        // TODO delete
//        $return = $this->getAssoc("SELECT DISTINCT $ordersTable.order_id as id,
//                  CONCAT(entities.prefix, ' ', $ordersTable.order_id, ', ', $dateField) as name
//                  FROM $ordersTable
//                  LEFT JOIN payments ON (payments.order_id = $ordersTable.order_id AND payments.category IN ($category))
//                  LEFT JOIN legal_entities entities ON (payments.legal_entity_id = entities.legal_entity_id)
//                  WHERE $by = $clientId");
        return $return;
    }

    public function getContractorName($category, $contractorId)
    {
        $contractorsList = $this->getSelectByCategory($category);
        foreach ($contractorsList as $contractor) {
            if ($contractor['id'] == $contractorId)
                return $contractor['name'];
        }
    }

    public function getExpenses()
    {
        $expenses = [];
        $expenseCategories = $this->getAssoc("SELECT category_id as id, name FROM category_of_expense 
                ORDER BY name ASC");
        $expenseArticles = $this->getAssoc("SELECT article_id as id, name, category_id FROM article_of_expense 
                ORDER BY name ASC");
        if (!empty($expenseCategories)) {
            foreach ($expenseCategories as $expenseCategory) {
                $id = $expenseCategory['id'];
                $name = $expenseCategory['name'];
                $values = [];
                foreach ($expenseArticles as $key => $expenseArticle) {
                    $articleId = $expenseArticle['id'];
                    if ($id == $expenseArticle['category_id']) {
                        $values[$articleId] = $expenseArticle['name'];
                        unset($expenseArticles[$key]);
                    }
                }

                $expenses[$id] = [
                    'name' => $name,
                    'values' => $values
                ];

            }
        }

        return array_values($expenses);
    }

    public function addNewContractorByCategory($category, $name)
    {

        switch ($category) {
            case 'Client':
                $base = ['clients'];
                break;
            case 'Comission Agent':
                $base = ['clients', COMISSION_AGENT];
                break;
            case 'Supplier':
                $base = [ 'suppliers'];
                break;
            case 'Customs':
                $base = ['customs'];
                break;
            case 'Delivery':
                $base = ['transportation_companies'];
                break;
            case 'Other':
                $base = ['other'];
                break;
        }

        if (empty($base))
            return false;

        $valuesNames = "`name`";
        $values = "'$name'";
        if (isset($base[1])) {
            $valuesNames .= ", `type`";
            $values .= ", '$base[1]'";
        }

        return $this->insert("INSERT INTO $base[0] ($valuesNames) VALUES ($values)");
    }

    public function savePayment($form, $paymentId)
    {
        $result = false;
        if (isset($form['new_contractor']) && $form['new_contractor']) {
            $form['contractor_id'] = $this->addNewContractorByCategory($form['category'], $form['new_contractor']);
            unset($form['new_contractor']);
        }
        if ($paymentId == 'new') {
            $valuesArray = [];
            $fieldsArray = [];
            foreach ($form as $field => $value) {
                $value = $value != "" ? $this->escape_string($value) : '';
                if (in_array($field, ['sum', 'sum_in_eur', 'currency_rate'])) {
                    $value = +str_replace(',', '.', $value);
                }
                $fieldsArray[] = "$field";
                $valuesArray[] = "'$value'";
            }
            $values = join(', ', $valuesArray);
            $fieldsString = join(', ', $fieldsArray);
            $id = $this->insert("INSERT INTO payments ($fieldsString) VALUES ($values)");
            $this->updateOrderPayment($id);
            return $id;
        } else {
            $setArray = [];
            foreach ($form as $name => $value) {
                $value = trim($value);
                if (!$value)
                    continue;
                $value = $value != "" ? $this->escape_string($value) : '';
                if (in_array($name, ['sum', 'sum_in_eur', 'currency_rate'])) {
                    $value = +str_replace(',', '.', $value);
                }
                $setArray[] = "`$name` = '$value'";
            }
            $set = join(', ', $setArray);
            $result = $this->update("UPDATE payments 
                          SET $set WHERE payment_id = $paymentId");
        }
        $this->updateOrderPayment($paymentId);
        return $result;
    }

    public function turnToEuro($currency, $money)
    {
        switch ($currency) {
            case 'USD':
                return $money * 0.96;
                break;
            case 'РУБ':
                return $money * 0.016;
                break;
            case 'EUR':
                return $money;
        }
        return $money;
    }

    public function printDoc($paymentId, $type = '')
    {
        $payment = $this->getFirst("SELECT * FROM payments WHERE payment_id = $paymentId");
        $orderId = $payment['order_id'];
        $order = $this->getFirst("SELECT * FROM orders WHERE order_id = $orderId");
        $fileName = 'single_payment';

        if ($payment && $order) {

            $values = [];
            $values['date'] = $payment['date'];
            $values['order_date'] = $order['start_date'];
            $values['vis_order_id'] = $order['visible_order_id'];
            $values['payment_id'] = $paymentId;
            $values['sum'] = round($payment['sum'], 2);
            $values['currency'] = $payment['currency'];

            require dirname(__FILE__) . '/../classes/NumbersToStrings.php';
            $values['sum_string'] = NumbersToStrings::num2str($values['sum'], $payment['currency']);

            require dirname(__FILE__) . "/../../assets/PHPWord_CloneRow-master/PHPWord.php";
            $phpWord =  new PHPWord();
            $docFile = dirname(__FILE__) . "/../../docs/templates/$fileName.docx";

            $templateProcessor = $phpWord->loadTemplate($docFile);
            foreach ($values as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }
            $templateProcessor->save(dirname(__FILE__) . "/../../docs/ready/$fileName.docx");

            return "/docs/ready/$fileName.docx";
        }
    }

    public function getDocuments($payment_id)
    {
        $docs = [
            [
                'href' => "/payment/print_doc?payment_id=$payment_id",
                'name' => 'Print'
            ],
        ];
        return $docs;
    }

    public function getPurpose($paymentArray)
    {

        $purpose = '';
        if (!empty($paymentArray)) {
            if (isset($paymentArray['order_id']) && $orderId = $paymentArray['order_id']) {
                if (isset($paymentArray['category']) && $paymentArray['category'] == 'Client') {
                    $order = $this->getFirst("SELECT visible_order_id, start_date, total_downpayment 
                                                FROM orders WHERE order_id = $orderId");
                    if ($order && $order['visible_order_id'] && isset($order['total_downpayment']) &&
                        isset($order['start_date'])) {
                        $date = date('d-m-Y', strtotime($order['start_date']));
                        $visible = $order['visible_order_id'];
                        $downpayment_rate = round($order['total_downpayment']);
                        $purpose = "Order $visible on $date client downpayment $downpayment_rate%";
                    }
                }
            }
            if (isset($paymentArray['purpose_of_payment']) && $paymentArray['purpose_of_payment'])
                $purpose = $paymentArray['purpose_of_payment'];
        }
        return $purpose;

    }
}