<?php

class ControllerCatalogue extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelCatalogue();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Catalogue";

        $this->getAccess('catalogue', 'v');
        $roles = new Roles();
        $this->view->full_product_column_names = $roles->returnModelNames($this->model->full_product_column_names, 'catalogue');
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;

        $this->view->brands = $this->model->getAll("brands");
        $this->view->wood = $this->model->getAll("wood");
        $this->view->colors = $this->model->getAll("colors");
        $this->view->constructions = $this->model->getAll("constructions");
        $this->view->grading = $this->model->getAll("grading");
        $this->view->patterns = $this->model->getAll("patterns");
        $this->view->tabs = $this->model->getCategoryTabs();

        $this->view->access = $roles->returnAccessAbilities('catalogue', 'ch');

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
        $id = isset($_GET['products']['id']) ? $_GET['products']['id'] : false;
        $this->model->getDTProducts($_GET, $id);
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
}