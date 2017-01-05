<?php

class ModelAccountant extends Model
{
    var $payments_columns = [
        array('dt' => 0, 'db' => "payments.payment_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"/payment?id=', payments.payment_id, '\">', payments.payment_id, '</a>')"),
        array('dt' => 2, 'db' => "payments.date"),
        array('dt' => 3, 'db' => "entities.name"),
        array('dt' => 4, 'db' => "payments.category"),
        array('dt' => 5, 'db' => "CONCAT('<a href=\"/', 
            IF(payments.category = 'Client' OR payments.category = 'Comission Agent', 'client?id=', 
            IF(payments.category = 'Supplier', 'supplier?id=', 
            IF(payments.category = 'Customs', 'custom?id=', 
            IF(payments.category = 'Delivery', 'transportation?id=', '')))), payments.contractor_id, '\"\">', 
            payments.contractor_id, '</a>')"),
        array('dt' => 6, 'db' => "CONCAT('<a href=\"/', IF(payments.category = 'Supplier', 
            'suppliers_order?id=', 'order?id='), payments.order_id, '\"\">', payments.order_id, '</a>')"),
        array('dt' => 7, 'db' => "transfers.name"),
        array('dt' => 8, 'db' => "payments.	currency"),
        array('dt' => 9, 'db' => "payments.sum"),
        array('dt' => 10, 'db' => "payments.direction"),
        array('dt' => 11, 'db' => "payments.sum_in_eur"),
        array('dt' => 12, 'db' => "payments.currency_rate"),
        array('dt' => 13, 'db' => "payments.purpose_of_payment"),
        array('dt' => 14, 'db' => "CONCAT(users.first_name, ' ', users.last_name)"),
        array('dt' => 15, 'db' => "CONCAT('<span class=\"label label-', IF(payments.status = 'Executed', 
            'success', 'default'), '\">', payments.status, '</span>')"),
        array('dt' => 16, 'db' => "CONCAT('<div style=\'width: 100%; text-align: center;\'>',
                        CONCAT('<a href=\"/accountant/delete?payment_id=', payments.payment_id,
                        '\" onclick=\"return confirm(\'Are you sure to delete the payment?\')\"><span class=\'glyphicon glyphicon-trash\' title=\'Delete\'></span></a>'),
                '</div>')")
    ];

    var $payments_column_names = [
        '_payment_id',
        'Payment Id',
        'Payment Date',
        'Legal entity',
        'Category',
        'Contractor Id',
        'Order Id',
        'Transfer Type',
        'Currency',
        'Sum',
        'Direction',
        'Currency Rate',
        'Sum in EURO',
        'Purpose of payment',
        'Responsible Person',
        'Status',
        'Actions'
    ];

    var $payments_table = 'payments
                            left join legal_entities as entities on entities.legal_entity_id = payments.legal_entity_id
                            left join transfers on transfers.transfer_id = payments.transfer_type_id
                            left join users on users.user_id = payments.responsible_person_id';

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTPayments($input)
    {
        $this->sspComplex($this->payments_table, "payments.payment_id", $this->payments_columns, $input, null,
            "payments.is_deleted = 0");
    }

    function getDTOrderPayments($order_id, $type, $input)
    {
        $columns = [
            array('dt' => 0, 'db' => "payments.payment_id"),
            array('dt' => 1, 'db' => "CONCAT('<a href=\"/payment?id=', payments.payment_id, '\">', payments.payment_id, '</a>')"),
            array('dt' => 2, 'db' => "payments.date"),
            array('dt' => 3, 'db' => "entities.name"),
            array('dt' => 4, 'db' => "payments.category"),
            array('dt' => 5, 'db' => "payments.contractor_id"),
            array('dt' => 6, 'db' => "payments.order_id"),
            array('dt' => 7, 'db' => "transfers.name"),
            array('dt' => 8, 'db' => "payments.	currency"),
            array('dt' => 9, 'db' => "payments.sum"),
            array('dt' => 10, 'db' => "payments.direction"),
            array('dt' => 11, 'db' => "payments.currency_rate"),
            array('dt' => 12, 'db' => "payments.sum_in_eur"),
            array('dt' => 13, 'db' => "payments.purpose_of_payment"),
            array('dt' => 14, 'db' => "CONCAT(users.first_name, ' ', users.last_name)"),
            array('dt' => 15, 'db' => "CONCAT('<span class=\"label label-', IF(payments.status = 'Executed', 
            'success', 'default'), '\">', payments.status, '</span>')"),
        ];

        $where = "payments.order_id = $order_id AND category = '$type' AND payments.is_deleted = 0";

        return $this->sspComplex($this->payments_table, "payments.payment_id", $columns,
            $input, null, $where);
    }

    function deletePayment($payment_id)
    {
        $this->update("UPDATE `payments`
                              SET `is_deleted` = 1 WHERE payment_id = $payment_id");
//        $this->delete("DELETE FROM payments WHERE payment_id = $payment_id");
    }

    function initParser($array)
    {

        $brands = $this->getAssoc("SELECT * FROM brands");

        function getBrandId($brandStr, $brands) {
            foreach ($brands as $brand) {
                if ($brand['name'] == $brandStr)
                    return $brand['brand_id'];
            }
            return null;
        }

        $gradings = $this->getAssoc("SELECT * FROM grading");

        function getGradingId($gradingStr, $gradings) {
            foreach ($gradings as $grading) {
                if ($grading['name'] == $gradingStr)
                    return $grading['grading_id'];
            }
            return null;
        }

        foreach ($array as $item) {

        $brand = $item['brand']['val'];
        $item['brand_id'] = [
            'val' => getBrandId($brand, $brands),
            'type' => 'int'
        ];
        unset($item['brand']);
        $grading = $item['grading']['val'];
        $item['grading_id'] = [
            'val' => getGradingId($grading, $gradings),
            'type' => 'int'
        ];
        unset($item['grading']);

        $names = '';
        $values = '';

        foreach ($item as $name => $valsArray) {
            $value = trim($valsArray['val']);
            $type = $valsArray['type'];
            if (!$value)
                continue;
            $value = mysql_real_escape_string($value);
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

            $this->insert("INSERT INTO new_products ($names status)
                          VALUES ($values 0)");
        }

    }

}