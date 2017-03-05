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
        return $this->getAssoc("SELECT order_id as id, CONCAT(order_id, ', ', $dateField) as name FROM $ordersTable 
                  WHERE $by = $clientId");
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
        $expenseCategories = $this->getAssoc("SELECT category_id as id, name FROM category_of_expense");
        $expenseArticles = $this->getAssoc("SELECT article_id as id, name, category_id FROM article_of_expense");
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

        return $expenses;
    }

    public function savePayment($form, $paymentId)
    {
        $result = false;
        if ($paymentId == 'new') {
            $valuesArray = [];
            $fieldsArray = [];
            foreach ($form as $field => $value) {
                $value = $value != "" ? $this->escape_string($value) : '';
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
            $values['sum'] = $payment['sum'];

            require dirname(__FILE__) . '/../classes/NumbersToStrings.php';
            $values['sum_string'] = NumbersToStrings::num2str($values['sum']);

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
}