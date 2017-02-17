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
                $this->view->full_product_column_names = $this->model->full_product_column_names;
                $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;
                $roles = new Roles();
                $this->view->access = $roles->returnAccessAbilities('brand', 'ch');

                $this->view->brands = $this->model->getAll("brands");
                $this->view->colors = $this->model->getAll("colors");
                $this->view->constructions = $this->model->getAll("constructions");
                $this->view->wood = $this->model->getAll("wood");
                $this->view->grading = $this->model->getAll("grading");
                $this->view->patterns = $this->model->getAll("patterns");
                $this->view->tabs = $this->model->getCategoryTabs();

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