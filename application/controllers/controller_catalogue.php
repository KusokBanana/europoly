<?php

class ControllerCatalogue extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelCatalogue();
        parent::afterConstruct();
    }

    public $page = 'catalogue';

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Catalogue";

        $this->getAccess($this->page, 'v');
        $roles = new Roles();
        $this->view->tableName = $this->model->tableName;
        $this->view->column_names = $this->model->getColumns($this->model->full_product_column_names,
            $this->page, $this->model->tableName, true);
        $this->view->hidden_columns = $this->model->full_product_hidden_columns;
        $this->view->originalColumns = $roles->returnModelNames($this->model->full_product_column_names, $this->page);

        $this->view->brands = $this->model->getAll("brands");
        $this->view->wood = $this->model->getAll("wood");
        $this->view->colors = $this->model->getAll("colors");
        $this->view->constructions = $this->model->getAll("constructions");
        $this->view->grading = $this->model->getAll("grading");
        $this->view->patterns = $this->model->getAll("patterns");
        $this->view->tabs = $this->model->getCategoryTabs();
        $this->view->categories = $this->model->getAll('category');

        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $cache = new Cache();
        $selectsCache = $cache->read('catalogue_selects');
        if (!empty($selectsCache)) {
            $array = $selectsCache;
            $selects = $array['selects'];
            $rows = $array['rows'];
        } else {
            $array = $this->model->getSelects();
            $selects = $array['selects'];
            $rows = $array['rows'];
            $cache->write('catalogue_selects', $array);
        }
        $this->view->selects = $selects;
        $this->view->rows = $rows;

        // try to read cache
        $selectsCache = $cache->read('new_product_selects');
        if (!empty($selectsCache)) {
            $new_product_selects = $selectsCache;
        } else {
            $new_product_selects = $this->model->newProductSelects();
            $cache->write('new_product_selects', $new_product_selects);
        }
        $this->view->new_product_selects = $new_product_selects;

        $this->view->build('templates/template.php', 'catalogue.php');
    }

    function action_dt()
    {
        $print = isset($_POST['print']) ? $_POST['print'] : false;
        if ($print) {
            $print = [
                'visible' => isset($_POST['visible']) && $_POST['visible'] ? json_decode($_POST['visible'], true) : [],
                'selected' => isset($_POST['selected']) && $_POST['selected'] ? json_decode($_POST['selected'], true) : [],
                'filters' => isset($_POST['filters']) && $_POST['filters'] ? json_decode($_POST['filters'], true) : [],
            ];
        }
        $table = isset($_GET['table']) ? $_GET['table'] : false;
        $page = isset($_GET['page']) ? $_GET['page'] : false;

        $this->model->getDTProducts($_POST, $print, $table, $page);
    }

    function action_add()
    {
        $this->getAccess('catalogue', 'ch');
        $postArray = [];
        $rusArray = [];
        foreach ($_POST as $key => $post) {
            if ($key == 'RUS' && !empty($post)) {
                foreach ($post as $rusKey=>$rusValue) {
                    $rusArray[$rusKey] = $this->escape_and_empty_to_null($rusValue);
                }
                unset($_POST['RUS']);
            }
            $postArray[$key] = $this->escape_and_empty_to_null($post);
        }
        $this->model->addProduct($postArray, $rusArray);
        header("Location: /catalogue");
    }

    function action_similar_product()
    {
        $this->getAccess('catalogue', 'ch');
        $productId = (isset($_GET['product_id']) && $_GET['product_id']) ? intval($_GET['product_id']) : 0;
        if ($productId) {
            echo $this->model->getProductValuesForSimilar($productId);
        }
    }

    function action_get_russian_value()
    {

        $field = (isset($_POST['field']) && $_POST['field']) ? $_POST['field'] : false;
        $table = (isset($_POST['table']) && $_POST['table']) ? $_POST['table'] : false;
        $value = (isset($_POST['value']) && $_POST['value']) ? $_POST['value'] : false;
        if ($field && $value) {
            echo $this->model->getRusValue($field, $value, $table);
        }

    }

//    function action_sample()
//    {
//        $productsGlobal = [];
//
//        for ($i=1; $i < 4; $i++) {
//
//            $file = dirname(__DIR__) . "/parse$i.txt";
//
//            $products = file_get_contents($file, FILE_USE_INCLUDE_PATH);
//
//            $products = json_decode($products, true);
//
//            $productsGlobal = array_merge($productsGlobal, $products);
//
//        }
//        echo '<pre>';
//        print_r($productsGlobal);
//        echo '</pre>';
//
////        set_time_limit(0);
//
//        for ($i = 667; $i < count($productsGlobal); $i++) {
//
////        }
////        foreach ($productsGlobal as $key => $oneProduct) {
//            $product_id = $i;
//            if (isset($productsGlobal[$i]))
//                $oneProduct = $productsGlobal[$i];
//            else
//                break;
//
//            $weight = (float) ($oneProduct['weight']['val']);
//            $purchase_price = (float) ($oneProduct['purchase_price']['val']);
//            $sell_price = (double) ($oneProduct['sell_price']['val']);
//            $visual_name = trim($oneProduct['visual_name']['val']);
//            $visual_name_rus = trim($oneProduct['visual_name_rus']['val']);
//            $visual_name = addslashes($visual_name);
//            $visual_name = htmlspecialchars($visual_name);
//            $visual_name = strip_tags($visual_name);
//            $visual_name_rus = addslashes($visual_name_rus);
//            $visual_name_rus = htmlspecialchars($visual_name_rus);
//            $visual_name_rus = strip_tags($visual_name_rus);
//            $set = ($weight && $weight !== null && $weight !== 'null' ?
//                    "weight = " . $weight . ", " : '') .
//                ($purchase_price && $purchase_price !== null && $purchase_price !== 'null' ?
//                    "purchase_price = " . $purchase_price . ", " : '') .
//                ($sell_price && $sell_price !== null && $sell_price !== 'null' ?
//                    "sell_price = " . $sell_price . ", " : '') .
//                ($visual_name && $visual_name !== null && $visual_name !== 'null' ?
//                    "visual_name = '" . $visual_name . "', " : '');
//
//            if ($set) {
//                $update = "UPDATE products SET $set change_time = NOW() WHERE product_id = $product_id";
//                echo '<br>' . $update;
//                $this->model->update($update);
//            }
//
//            if ($visual_name_rus && $visual_name_rus !== null && $visual_name_rus !== 'null') {
//                $update = "UPDATE nls_products SET visual_name = '$visual_name_rus' WHERE product_id = $product_id";
//                echo '<br>' . $update;
//                $this->model->update($update);
//            }
//        }
//
//        echo '<br><br> Job is Done, My Master!';
//        die();
//    }

}