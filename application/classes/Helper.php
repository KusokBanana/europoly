<?php

/**
 * Created by PhpStorm.
 * User: kusok
 * Date: 01.06.2017
 * Time: 12:42
 */
class Helper
{

    public static function arrGetVal($arr, $index)
    {
        return isset($arr[$index]) ? $arr[$index] : false;
    }

    public static function varDump($array, $isWithTypes = false)
    {
        echo '<br><pre>';
        if ($isWithTypes)
            var_dump($array);
        else
            print_r($array);
        echo '</pre><br>';
    }

    public static function safeVar($var)
    {
        if (!$var || is_null($var))
            return false;

        $var = trim($var);
//        $var = mysql_real_escape_string($var);
//        $var = htmlspecialchars($var);
        $var = addslashes($var);
//        $var = htmlspecialchars($var);
        $var = strip_tags($var);
        return $var;
    }

    public static function getNamesAndValues($item)
    {

        $names = [];
        $values = [];
        foreach ($item as $name => $value) {
            if ($value && $value !== null && strtolower($value) !== 'null') {
                $names[] = $name;
                $values[] = $value;
            }
        }
        $names = implode("`,`", $names);
        $values = implode("','", $values);

        return ['names' => $names, 'values' => $values];

    }

    public static function getOrderItemLabel($name) {
        switch ($name) {
            case 'product_id':
                return "Product";
            case 'purchase_price':
                return "Purchase Price";
            case 'discount_rate':
                return "Discount Rate";
            case 'manager_bonus_rate':
                return "Manager Bonus Rate";
            case 'status_id':
                return "Status";
            case 'commission_rate':
                return "Commission Rate";
            case 'supplier_order_id':
                return "Supplier Order";
            case 'truck_id':
                return "Truck";
            case 'warehouse_arrival_date':
                return "Warehouse Arrival Date";
            case 'import_tax':
                return "Import Tax";
            case 'delivery_price':
                return "Delivery Price";
            case 'import_VAT':
                return 'Import VAT';
            case 'import_brokers_price':
                return "Import Brokers Price";
            case 'warehouse_id':
                return "Warehouse";
            case 'buy_price':
                return 'Buy Price';
            case 'buy_and_taxes':
                return "Buy And Taxes";
            case 'dealer_price';
                return "Dealer Price";
            case 'reserve_since_date':
                return "Reserve Since Date";
            case 'reserve_till_date':
                return 'Reserve Till Date';
            case 'production_date':
                return 'Production Date';
            case 'issue_date':
                return "Issue Date";
            case 'return_date':
                return "Return Date";
            default:
                return 'Some Field';
        }
    }

}