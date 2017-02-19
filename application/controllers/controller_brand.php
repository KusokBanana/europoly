<?php

class ControllerBrand extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelBrand();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if (isset($_GET["id"])) {
            $this->getAccess('brand', 'v');
            $id = intval($_GET["id"]);
            $this->view->brand = $this->model->getById("brands", "brand_id", $id);
            if ($this->view->brand != NULL) {
                $this->view->title = $this->view->brand["name"];
                $roles = new Roles();
                $this->view->full_product_column_names = $roles->returnModelNames($this->model->full_product_column_names, 'catalogue');
                $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;

                $cache = new Cache();
                $selectsCache = $cache->read('brand_catalogue_selects');
                if (!empty($selectsCache)) {
                    $array = $selectsCache;
                    $selects = $array['selects'];
                    $rows = $array['rows'];
                } else {
                    $array = $this->model->getSelectsBrand($id);
                    $selects = $array['selects'];
                    $rows = $array['rows'];
                    $cache->write('brand_catalogue_selects', $array);
                }
                $this->view->selects = $selects;
                $this->view->rows = $rows;

                $this->view->access = $roles->returnAccessAbilities('brand', 'ch');

                $this->view->brands = $this->model->getAll("brands");
                $this->view->colors = $this->model->getAll("colors");
                $this->view->constructions = $this->model->getAll("constructions");
                $this->view->wood = $this->model->getAll("wood");
                $this->view->grading = $this->model->getAll("grading");
                $this->view->patterns = $this->model->getAll("patterns");
                $this->view->tabs = $this->model->getCategoryTabs();

                // try to read cache
                $selectsCache = $cache->read('new_product_selects');
                if (!empty($selectsCache)) {
                    $new_product_selects = $selectsCache;
                } else {
                    $new_product_selects = $this->model->newProductSelects();
                    $cache->write('new_product_selects', $new_product_selects);
                }
                $this->view->new_product_selects = $new_product_selects;

                $this->view->build('templates/template.php', 'single_brand.php');
            } else {
                http_response_code(400);
            }
        } else {
            http_response_code(400);
        }
    }

    function action_dt()
    {
        if (isset($_GET["products"])) {
            $data = $_GET["products"];
            $brand_id = intval($data['brand_id']);

            $this->model->getDTProductsForBrand($brand_id, $_GET);
        } else {
            http_response_code(400);
        }
    }
}