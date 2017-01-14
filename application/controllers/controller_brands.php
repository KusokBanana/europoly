<?php

class ControllerBrands extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelBrands();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Brands";
        $this->view->suppliers = $this->model->getSuppliersIdNames();
        $this->view->build('templates/template.php', 'brands.php');
    }

    function action_dt()
    {
        $this->model->getDTBrands($_GET);
    }

    function action_add()
    {
        $brand_id = $this->model->addBrand($this->escape_and_empty_to_null($_POST['name']),
            $this->escape_and_empty_to_null($_POST['supplier']));
        header("Location: /brand?id=" . $brand_id);
    }
}