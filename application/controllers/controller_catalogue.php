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
        $this->view->full_product_column_names = $this->model->full_product_column_names;
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;

        $this->view->brands = $this->model->getAll("brands");
        $this->view->wood = $this->model->getAll("wood");
        $this->view->colors = $this->model->getAll("colors");
        $this->view->constructions = $this->model->getAll("constructions");
        $this->view->grading = $this->model->getAll("grading");
        $this->view->patterns = $this->model->getAll("patterns");
        $this->view->tabs = $this->model->getCategoryTabs();



        $this->view->build('templates/template.php', 'catalogue.php');
    }

    function action_dt()
    {
        $id = isset($_GET['products']['id']) ? $_GET['products']['id'] : false;
        $this->model->getDTProducts($_GET, $id);
    }

    function action_add()
    {
        $this->model->addProduct(
            $this->escape_and_empty_to_null($_POST['article']),
            $this->escape_and_empty_to_null($_POST['name']),
            $this->escape_and_empty_to_null($_POST['brand_id']),
            $this->escape_and_empty_to_null($_POST['country']),
            $this->escape_and_empty_to_null($_POST['collection']),
            $this->escape_and_empty_to_null($_POST['wood_id']),
            $this->escape_and_empty_to_null($_POST['additional_info']),
            $this->escape_and_empty_to_null($_POST['color_id']),
            $this->escape_and_empty_to_null($_POST['color2_id']),
            $this->escape_and_empty_to_null($_POST['grading_id']),
            $this->escape_and_empty_to_null($_POST['thickness']),
            $this->escape_and_empty_to_null($_POST['width']),
            $this->escape_and_empty_to_null($_POST['length']),
            $this->escape_and_empty_to_null($_POST['texture']),
            $this->escape_and_empty_to_null($_POST['layer']),
            $this->escape_and_empty_to_null($_POST['installation']),
            $this->escape_and_empty_to_null($_POST['surface']),
            $this->escape_and_empty_to_null($_POST['construction_id']),
            $this->escape_and_empty_to_null($_POST['units']),
            $this->escape_and_empty_to_null($_POST['packing_type']),
            $this->escape_and_empty_to_null($_POST['weight']),
            $this->escape_and_empty_to_null($_POST['amount']),
            $this->escape_and_empty_to_null($_POST['purchase_price']),
            $this->escape_and_empty_to_null($_POST['currency']),
            $this->escape_and_empty_to_null($_POST['suppliers_discount']),
            $this->escape_and_empty_to_null($_POST['margin']),
            $this->escape_and_empty_to_null($_POST['pattern_id']),
            $this->escape_and_empty_to_null(0));
        header("Location: /catalogue");
    }
}