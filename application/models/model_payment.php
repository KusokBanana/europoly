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
                $select = (!$contractorId) ? $this->getClientsByType('Commission Agent') :
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
                $select = false;
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
                $setArray[] = "$name = '$value'";
            }
            $set = join(', ', $setArray);
            $this->update("UPDATE payments 
                          SET $set WHERE payment_id = $paymentId");
        }
        $this->updateOrderPayment($paymentId);
    }

    public function updateOrderPayment($payment_id)
    {
        $payment = $this->getFirst("SELECT order_id, category FROM payments 
              WHERE payment_id = $payment_id AND is_deleted = 0");
        $orderId = $payment['order_id'];
        $category = $payment['category'];
        $allPaymentsForOrder = $this->getAssoc("SELECT sum_in_eur FROM payments 
          WHERE (order_id = $orderId AND category = '$category' AND is_deleted = 0)");
        $totalSum = 0;
        $order = $this->getFirst("SELECT total_price FROM orders WHERE order_id = $orderId");
        if (!empty($allPaymentsForOrder))
            foreach ($allPaymentsForOrder as $onePaymentForOrder) {
//                $totalSum += $this->turnToEuro($onePaymentForOrder['currency'], $onePaymentForOrder['sum']);
                $totalSum += $onePaymentForOrder['sum_in_eur'];
            }
        $rate = $totalSum / $order['total_price'] * 100;
        if ($category == 'Client' || $category == 'Comission Agent') {
            $this->update("UPDATE orders SET total_downpayment = $totalSum, downpayment_rate = $rate 
                                  WHERE order_id = $orderId");
        } else if ($category == 'Supplier') {
            $this->update("UPDATE suppliers_orders SET total_downpayment = $totalSum  
                            WHERE order_id = $orderId");
            // TODO add here downpayment_rate too
        }

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
}