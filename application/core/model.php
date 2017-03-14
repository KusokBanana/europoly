<?php
include_once("mysql_config.php");
include_once ('config.php');

abstract class Model extends mysqli
{
    var $sql_details = array(
        'user' => SQLUSER,
        'pass' => SQLPWD,
        'db' => SQLDB,
        'host' => SQLADDR
    );

    public function connect_db()
    {
        parent::__construct(SQLADDR, SQLUSER, SQLPWD, SQLDB);
        $this->query("SET NAMES utf8");
    }

    public function insert($query)
    {
//        if ($_SESSION["user_role"] != 'admin') {
//            die("You have no rights to insert, current role: " . $_SESSION["user_role"]);
//        }
        $result = $this->query($query);
        if (!$result) {
            echo mysqli_error($this);
            die("Mysqli: error while insert; query: " . $query);
        }
        return $this->insert_id;
    }

    public function update($query)
    {
//        if ($_SESSION["user_role"] != 'admin') {
//            die("You have no rights to update, current role: " . $_SESSION["user_role"]);
//        }
        $result = $this->query($query);
        if (!$result) {
            echo mysqli_error($this);
            die("Mysqli: error while update, current role: " . $_SESSION["user_role"]);
        }
        return $this->affected_rows > 0;
    }

    public function getMax($query)
    {
        $result = $this->query($query);
        if ($result) {
            $row = mysqli_fetch_row($result);
            if (!empty($row) && isset($row[0]) && $maxId = $row[0]) {
                return $maxId;
            }
        }
        echo mysqli_error($this);
        die("Mysqli: error while select max");
    }

    public function delete($query)
    {
//        if ($_SESSION["user_role"] != 'admin') {
//            die("You have no rights to delete, current role: " . $_SESSION["user_role"]);
//        }
        $result = $this->query($query);
        if (!$result) {
            echo mysqli_error($this);
            die("Mysqli: error while delete");
        }
    }

    public function getById($table_name, $column_name, $id)
    {
        return $this->getFirst("SELECT * FROM `$table_name` WHERE `$column_name` = $id");
    }

    public function getFirst($query)
    {
        if ($result = $this->query($query)) {
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        }
        return NULL;
    }

    public function getAll($table_name)
    {
        return $this->getAssoc("SELECT * FROM `$table_name`");
    }

    public function getAssoc($query)
    {
        $list = [];
        if ($result = $this->query($query)) {
            while ($row = $result->fetch_assoc()) {
                array_push($list, $row);
            }
        }
        return $list;
    }

    protected function sspSimple($table, $primaryKey, $columns, $input)
    {
        echo  $this->getSspSimpleJson($table, $primaryKey, $columns, $input);
    }

    protected function sspComplex($table, $primaryKey, $columns, $input, $whereResult = null, $whereAll = null)
    {
        echo $this->getSspComplexJson($table, $primaryKey, $columns, $input, $whereResult, $whereAll);
    }

    protected function getSspComplexJson($table, $primaryKey, $columns, $input, $whereResult = null, $whereAll = null)
    {
        include_once('ssp.class.php');
        return json_encode(SSP::complex($input, $this->sql_details, $table, $primaryKey, $columns, $whereResult, $whereAll));
    }

    protected function getSspSimpleJson($table, $primaryKey, $columns, $input)
    {
        include_once('ssp.class.php');
        return json_encode(SSP::simple($input, $this->sql_details, $table, $primaryKey, $columns));
    }
    // SPECIFIC PROJECT STUFF
    //

    var $full_product_columns = array(
        array('dt' => 0, 'db' => 'products.product_id'),
        array('dt' => 1, 'db' => 'products.article'),
        array('dt' => 2, 'db' => "CONCAT('<a href=\"/product?id=', products.product_id, '\">', IFNULL(products.name, 'no name'), '</a>')"),
        array('dt' => 3, 'db' => "CONCAT('<a href=\"/brand?id=', brands.brand_id, '\">', IFNULL(brands.name, 'no name'), '</a>')"),
        array('dt' => 4, 'db' => 'suppliers.name'),
        array('dt' => 5, 'db' => 'products.country'),
        array('dt' => 6, 'db' => 'products.collection'),
        array('dt' => 7, 'db' => 'wood.name'),
        array('dt' => 8, 'db' => 'products.additional_info'),
        array('dt' => 9, 'db' => "CONCAT(colors.name, ', ', colors2.name)"),
        array('dt' => 10, 'db' => "products.color"),
        array('dt' => 11, 'db' => 'grading.name'),
        array('dt' => 12, 'db' => 'products.thickness'),
        array('dt' => 13, 'db' => 'products.width'),
        array('dt' => 14, 'db' => 'products.length'),
        array('dt' => 15, 'db' => 'products.texture'),
        array('dt' => 16, 'db' => 'products.layer'),
        array('dt' => 17, 'db' => 'products.installation'),
        array('dt' => 18, 'db' => 'products.surface'),
        array('dt' => 19, 'db' => 'constructions.name'),
        array('dt' => 20, 'db' => 'products.construction'),
        array('dt' => 21, 'db' => 'products.units'),
        array('dt' => 22, 'db' => 'products.packing_type'),
        array('dt' => 23, 'db' => 'products.weight'),
        array('dt' => 24, 'db' => 'CAST(products.amount_in_pack as decimal(64, 2))'),
        array('dt' => 25, 'db' => 'products.purchase_price'),
        array('dt' => 26, 'db' => 'products.currency'),
        array('dt' => 27, 'db' => 'products.suppliers_discount'),
        array('dt' => 28, 'db' => 'products.margin'),
        array('dt' => 29, 'db' => 'patterns.name'),
        array('dt' => 30, 'db' => 'CONCAT(IF(products.status=0, "Active", IF(products.status=1, "Limited Edition", IF(products.status=2, "Out Of Production", ""))))'),
        array('dt' => 31, 'db' => 'products.sell_price'),
        array('dt' => 32, 'db' => '"N/A"'),
        array('dt' => 33, 'db' => 'products.category_id')
    );

    var $full_product_hidden_columns = "[4, 5, 6, 10, 12, 14, 16, 17, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 32, 33]";

    var $full_product_column_names = array(
        '_product_id',
        'Article',
        'Name',
        'Brand',
        'Supplier',
        'Country',
        'Collection',
        'Wood',
        'Additional characteristics',
        'Color',
        'Color2',
        'Grading',
        'Thickness',
        'Width',
        'Length',
        'Texture',
        'Layer',
        'Installation',
        'Surface',
        'Construction',
        'Construction',
        'Units',
        'Packing Type',
        'Weight',
        'Quantity in 1 Pack',
        'Purchase price',
        'Currency',
        'Supplier\'s discount',
        'Margin',
        'Pattern',
        'Status',
        'Sell Price',
        'Price',
        '_category_id'
    );

    var $full_products_table = "products ".
            "left join brands on products.brand_id = brands.brand_id ".
            "left join suppliers on brands.supplier_id = suppliers.supplier_id ".
            "left join colors on products.color_id = colors.color_id ".
            "left join colors as colors2 on products.color2_id = colors2.color_id ".
            "left join constructions on products.construction_id = constructions.construction_id ".
            "left join wood on products.wood_id = wood.wood_id ".
            "left join grading on products.grading_id = grading.grading_id ".
            "left join patterns on products.pattern_id = patterns.pattern_id";

    var $full_products_table_addition = 'left join brands on products.brand_id = brands.brand_id
                                        left join suppliers on (brands.supplier_id = suppliers.supplier_id)
                                        left join colors on products.color_id = colors.color_id
                                        left join colors as colors2 on products.color2_id = colors2.color_id
                                        left join constructions on products.construction_id = constructions.construction_id
                                        left join wood on products.wood_id = wood.wood_id
                                        left join grading on products.grading_id = grading.grading_id
                                        left join patterns on products.pattern_id = patterns.pattern_id';

    function getSalesManagersIdName()
    {
        return $this->getAssoc("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name
          FROM users LEFT JOIN roles ON roles.role_id = users.role_id WHERE roles.role_id IN (2, 5)");
    }

    function getRolePermissions($roleId)
    {
        if ($roleId) {
            $permissions = $this->getFirst("SELECT permissions FROM roles WHERE role_id = $roleId");
            return $permissions ? $permissions['permissions'] : false;
        }

    }

    function getCommissionAgentsIdName()
    {
        return $this->getAssoc("SELECT client_id, name FROM clients WHERE type = '" . CLIENT_TYPE_COMISSION_AGENT . "'");
    }

    function getClientsIdName()
    {
        return $this->getAssoc("SELECT client_id, name FROM clients");
    }

    public function getCountriesIdName($query)
    {
        return $this->getAssoc("SELECT country_id AS id, name AS text FROM countries WHERE name LIKE '%$query%'");
    }

    public function getRegionsIdName($query, $country_id)
    {
        return $this->getAssoc("SELECT region_id AS id, name AS text FROM regions WHERE name LIKE '%$query%' AND country_id = $country_id");
    }

    function getOrder($order_id)
    {
        return $this->getById('orders', 'order_id', $order_id);
    }

    function getClient($client_id)
    {
        return $this->getById('clients', 'client_id', $client_id);
    }

    function getPayment($payment_id)
    {
        return $this->getById('payments', 'payment_id', $payment_id);
    }

    function getUser($user_id)
    {
        return $this->getById('users', 'user_id', $user_id);
    }

    function deleteUser($user_id)
    {
        return $this->update("UPDATE users SET is_deleted = 1 WHERE user_id = $user_id");
    }

    function getRoles()
    {
        $where = "WHERE role_id != ".ROLE_ADMIN;
        if ($_SESSION['user_role'] == ROLE_ADMIN)
            $where= '';
        return $this->getAssoc("SELECT role_id as id, name FROM roles $where");
    }

    function getItemStatusName($status_id)
    {
        $status = $this->getFirst("SELECT name FROM items_status WHERE status_id = $status_id");
        return ($status) ? $status['name'] : '';
    }

    function getClientsOfSalesManager($sales_manager_id)
    {
        return $this->getAssoc("SELECT client_id, name FROM clients WHERE sales_manager_id = $sales_manager_id");
    }

    function getCategoryTabs()
    {
        $categories = $this->getAll('category');
        $floors = ['name' => 'Floors', 'items' => [], 'id' => []];
        $windows = ['name' => 'Windows', 'items' => [], 'id' => []];
        $interior = ['name' => 'Interior Elements', 'items' => [], 'id' => []];
        $other = ['name' => 'Other', 'items' => [], 'id' => []];
        $all = ['name' => 'All'];
        foreach ($categories as $category) {
            $catId = $category['category_id'];
            $item = [
                'id' => $catId,
                'name' => $category['name']
            ];
            switch ($catId) {
                case 1:
                case 2:
                case 4:
                case 5:
                case 6:
                    $floors['items'][] = $item;
                    break;
                case 7:
                case 13:
                case 14:
                    $interior['items'][] = $item;
                    break;
                case 3:
                case 8:
                case 9:
                case 10:
                case 11:
                    $other['items'][] = $item;
                    break;
                case 12:
                    $windows = $item;
                    break;
            }
        }
        return [$all, $floors, $windows, $interior, $other];
    }

    public function updateOrderPayment($payment_id)
    {
        $payment = $this->getFirst("SELECT order_id, category FROM payments 
              WHERE payment_id = $payment_id AND is_deleted = 0");
        $orderId = $payment['order_id'];
        $category = $payment['category'];
        $allPaymentsForOrder = $this->getAssoc("SELECT * FROM payments 
          WHERE (order_id = $orderId AND category = '$category' AND is_deleted = 0)");
        $totalSum = 0;
        $order = $this->getFirst("SELECT total_price FROM orders WHERE order_id = $orderId");
        if (!empty($allPaymentsForOrder))
            foreach ($allPaymentsForOrder as $onePaymentForOrder) {
//                $totalSum += $this->turnToEuro($onePaymentForOrder['currency'], $onePaymentForOrder['sum']);
                if ($onePaymentForOrder['status'] == 'Executed' && $onePaymentForOrder['category'] == 'Client') {
                    $sumInEur = $onePaymentForOrder['sum_in_eur'];
                    $sumInEur = ($onePaymentForOrder['direction'] == 'Income') ? $sumInEur : -$sumInEur;
                    $totalSum += $sumInEur;
                }
            }
        $rate = $totalSum / $order['total_price'] * 100;
        if ($category == 'Client' || $category == CLIENT_TYPE_COMISSION_AGENT) {
            $this->update("UPDATE orders SET total_downpayment = $totalSum, downpayment_rate = $rate 
                                  WHERE order_id = $orderId");
        } else if ($category == 'Supplier') {
            $this->update("UPDATE suppliers_orders SET total_downpayment = $totalSum  
                            WHERE order_id = $orderId");
            // TODO add here downpayment_rate too
        }
    }

    public function getColumns($columns, $page, $tableId, $isNames = false)
    {
        $roles = new Roles();
        if ($isNames) {
            $columns = $roles->returnModelNames($columns, $page);
        } else {
            $columns = $roles->returnModelColumns($columns, $page);
        }
        return $this->reOrderColumns($columns, $tableId);
    }

    private function reOrderColumns($columns, $tableId)
    {
        $userId = $_SESSION['user_id'];
        $userOrder = $this->getFirst("SELECT columns_order FROM users WHERE user_id = $userId");
        $userOrder = $userOrder['columns_order'];
        if ($userOrder && $userOrder !== null) {
            $columnsRightOrder = json_decode($userOrder, true);
            if (!empty($columnsRightOrder) && isset($columnsRightOrder[$tableId]) && !empty($columnsRightOrder[$tableId])) {
                $columnsRightOrderForTable = $columnsRightOrder[$tableId];
                $isNames = !isset($columns[0]['dt']);
                $newColumns = [];
                $newColumns[] = $columns[0];
                foreach($columnsRightOrderForTable as $number) {
                    if (isset($columns[$number])) {
                        $newColumn = $columns[$number];
                        if (!$isNames) {
                            $lastIndex = intval($newColumns[count($newColumns) - 1]['dt']);
                            $newColumn['dt'] = $lastIndex + 1;
                        }
                        $newColumns[] = $newColumn;
                    }
                }
                if (($count = count($columns) - count($columnsRightOrderForTable)) > 1) {
                    for ($i=$count-2; $i >= 0; $i--) {
                        $newColumn = $columns[count($columns)-$i-1];
                        if (!$isNames) {
                            $newColumn['dt'] = count($newColumns);
                        }
                        $newColumns[] = $newColumn;
                    }
                }
                $columns = $newColumns;
            }
        }
        return $columns;
    }

    protected function unLinkStrings (&$array, $cols) {
        foreach ($cols as $col) {
            $array[$col]['formatter'] = function($d) {
                preg_match('/<a+[^>]+?[^>]+>(.*?)<\/a+>/i', $d, $match);
                if (!empty($match) && isset($match[1])) {
                    $d = $match[1];
                }
                return $d;
            };
        }
    }

    protected function getProductName($productId, $productArray = [], $isMulti = false)
    {
        if (!empty($productArray))
            $product = $productArray;
        else
            $product = $this->getFirst("SELECT * FROM products WHERE product_id = $productId");

        $name = [];
        $enName = [];
        $rusOrderItem = $this->getFirst("SELECT * FROM nls_products WHERE product_id = $productId");

        if ($brandId = $product['brand_id']) {
            $brand = $this->getFirst("SELECT name FROM brands WHERE brand_id = $brandId");
            if ($brand) {
                $rusBrand = $this->getFirst("SELECT name FROM nls_brands WHERE brand_id = $brandId");
                $brandName = ($rusBrand && $rusBrand['name']) ? $rusBrand['name'] : $brand['name'];
                if ($brandName)
                    $name[] = htmlspecialchars($brandName);
                if ($brand['name'])
                    $enName[] = htmlspecialchars($brand['name']);
            }
        }

        $collection = $rusOrderItem['collection'] ? $rusOrderItem['collection'] : $product['collection'];
        if ($collection)
            $name[] = htmlspecialchars($collection);
        if ($product['collection'])
            $enName[] = htmlspecialchars($product['collection']);

        if ($woodId = $product['wood_id']) {
            $wood = $this->getFirst("SELECT * FROM wood WHERE wood_id = $woodId");
            if ($wood) {
                $rusWood = $this->getFirst("SELECT value FROM nls_resources
                          WHERE nls_resource_id = ${wood['nls_resource_id']}");
                $woodName = ($rusWood && $rusWood['value']) ? $rusWood['value'] : $wood['name'];
                if ($woodName)
                    $name[] = htmlspecialchars($woodName);
                if ($wood['name'])
                    $enName[] = htmlspecialchars($wood['name']);
            }
        }
        if ($gradingId = $product['grading_id']) {
            $grading = $this->getFirst("SELECT * FROM grading WHERE grading_id = $gradingId");
            if ($grading) {
                $rusGrading = $this->getFirst("SELECT value FROM nls_resources
                          WHERE nls_resource_id = ${grading['nls_resource_id']}");
                $gradingName = ($rusGrading && $rusGrading['value']) ? $rusGrading['value'] : $grading['name'];
                if ($gradingName)
                    $name[] = htmlspecialchars($gradingName);
                if ($grading['name'])
                    $enName[] = htmlspecialchars($grading['name']);
            }
        }
        if ($colorId = $product['color_id']) {
            $color = $this->getFirst("SELECT * FROM colors WHERE color_id = $colorId");
            if ($color) {
                $rusColor = $this->getFirst("SELECT value FROM nls_resources WHERE
                          nls_resource_id = ${color['nls_resource_id']}");
                $colorName = ($rusColor && $rusColor['value']) ? $rusColor['value'] : $color['name'];
                if ($colorName)
                    $name[] = htmlspecialchars($colorName);
                if ($color['name'])
                    $enName[] = htmlspecialchars($color['name']);
            }
        }
        $texture = $rusOrderItem['texture'] ? $rusOrderItem['texture'] : $product['texture'];
        if ($texture)
            $name[] = htmlspecialchars($texture);
        if ($product['texture'])
            $enName[] = htmlspecialchars($product['texture']);

        $surface = $rusOrderItem['surface'] ? $rusOrderItem['surface'] : $product['surface'];
        if ($surface)
            $name[] = htmlspecialchars($surface);
        if ($product['surface'])
            $enName[] = htmlspecialchars($product['surface']);

        $size = '';
        if ($thickness = $product['thickness'])
            $size .= $thickness;
        if ($width = $product['width']) {
            if ($size)
                $size .= 'x';
            $size .= $width;
        }
        if ($length = $product['length']) {
            if ($size)
                $size .= 'x';
            $size .= $length;
        }

        if ($size) {
            $name[] = htmlspecialchars($size);
            $enName[] = htmlspecialchars($size);
        }
        if (!$isMulti)
            return implode(', ', $name);
        else
            return [
                'en' => implode(', ', $enName),
                'rus' => implode(', ', $name)
            ];
    }

    protected function getProductsDataArrayForDocPrint($items, $multi_lang_name = false)
    {
        $values = [
            'sum' => 0,
            'currency' => 'EUR',
            'amount' => 0,
            'total' => 0,
            'total_weight' => 0,
            'total_packs_number' => 0
        ];
        $products = [];

        foreach ($items as $id => $orderItem) {
            $id++;
            $productId = $orderItem['product_id'];
            $product = $this->getFirst("SELECT * FROM products WHERE product_id = $productId");
            $unitsRus = $this->getFirst("SELECT units FROM nls_products WHERE product_id = $productId");

            $units = ($unitsRus && $unitsRus['units']) ? $unitsRus['units'] : $product['units'];
            $reducedPrice = $orderItem['sell_price'] * (100 - $orderItem['discount_rate'])/100;
            $sum = floatval($reducedPrice * $orderItem['amount']);
            $weight = (is_null($product['weight'])) ? 0 : $product['weight'];

            $products['id'][] = $id;
            $products['article'][] = $product['article'];
            $products['units'][] = $units;
            $products['price'][] = round(floatval($reducedPrice), 2);
            $products['sum'][] = round($sum, 2);
            $products['pack_type'][] = $product['packing_type'];
            $products['weight'][] = $product['weight'];
            $products['amount'][] = round($orderItem['amount'], 2);
            $products['packs_number'][] = round($orderItem['number_of_packs'], 2);
            $products['production_date'][] = $orderItem['production_date'];

            $values['total_packs_number'] += $orderItem['number_of_packs'];
            $values['amount'] += $orderItem['amount'];
            $values['total_weight'] += !is_null($product['weight']) ? floatval($weight) : 0;
            $values['sum'] += $sum;
            $values['total']++;

            if ($multi_lang_name) {
                $names = $this->getProductName($productId, $product, true);
                $products['name'][] = $names['rus'];
                $products['en_name'][] = $names['en'];
            } else {
                $products['name'][] = $this->getProductName($productId, $product);
            }
        }

        $values['sum'] = round($values['sum'], 2);
        require dirname(__FILE__) . '/../classes/NumbersToStrings.php';
        $values['sum_string'] = NumbersToStrings::num2str($values['sum'], $values['currency']);
        $values['total_weight'] = round($values['total_weight'], 2);
        $values['total_packs_number'] = round($values['total_packs_number'], 2);
        $values['amount'] = round($values['amount'], 2);

        return ['products' => $products, 'values' => $values];
    }

    public function clearCache($names)
    {
        $cache = new Cache();
        if (is_array($names) && !empty($names)) {
            foreach ($names as $name) {
                $cache->delete($name);
            }
        } else {
            $cache->delete($names);
        }
    }

    public function getLogs()
    {

        $logs = $this->getAssoc("SELECT CONCAT(logging.action, ' - ', users.first_name, ' ', users.last_name, ' - ',
          logging.date) as name, CONCAT('/warehouse/print_log_doc?id=', logging.log_id) as href 
          FROM logging LEFT JOIN users ON (logging.user_id = users.user_id)");

        return $logs;

    }

    public function addLog($name, $info)
    {

        if ($name && $info) {

            if (is_array($info)) {
                if (empty($info))
                    return false;

                $info = json_encode($info);
            }

            $userId = $_SESSION['user_id'];

            $this->insert("INSERT INTO logging (action, info, user_id) VALUES ('$name', '$info', $userId)");

        }

    }
}