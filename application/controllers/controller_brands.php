<?php

class ControllerBrands extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelBrands();
        parent::afterConstruct();
    }

    public $page = 'brands';

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Brands";
        $roles = new Roles();
        $this->view->access = $roles->returnAccessAbilities($this->page, 'ch');
        $this->view->suppliers = $this->model->getSuppliersIdNames();

        $this->view->tableName = $this->model->tableName;
        $this->view->column_names = $this->model->getColumns($this->model->columnNames,
            $this->page, $this->model->tableName, true);
        $this->view->originalColumns = $roles->returnModelNames($this->model->columnNames, $this->page);

        $this->view->build('templates/template.php', 'brands.php');
    }

    function action_dt()
    {
        $this->model->getDTBrands($_GET);
    }

    function action_add()
    {
        $this->getAccess($this->page, 'ch');
        $brand_id = $this->model->addBrand($this->escape_and_empty_to_null($_POST['name']),
            $this->escape_and_empty_to_null($_POST['supplier']));
        header("Location: /brand?id=" . $brand_id);
    }

    function action_delete()
    {

        $id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : false;
        if ($id) {
            $this->model->deleteBrand($id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

    }
}