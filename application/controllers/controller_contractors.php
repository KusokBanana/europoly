<?php

class ControllerContractors extends Controller
{
    public $page = 'contractors';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelContractors();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Contractors";
        $this->view->column_names = $this->model->column_names;

        $this->view->clientsTable = $this->model->getTableData(PAYMENT_CATEGORY_CLIENT);
        $this->view->suppliersTable = $this->model->getTableData(PAYMENT_CATEGORY_SUPPLIER);
        $this->view->customsTable = $this->model->getTableData(PAYMENT_CATEGORY_CUSTOMS);
        $this->view->transportTable = $this->model->getTableData(PAYMENT_CATEGORY_DELIVERY);
        $this->view->otherTable = $this->model->getTableData(PAYMENT_CATEGORY_OTHER);

        $this->view->managers = $this->model->getSalesManagersIdName();
        $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
        $this->view->build('templates/template.php', 'contractors.php');
    }

    function action_dt_contractors()
    {
	    $type = Helper::arrGetVal($_GET, 'type');
	    $print = $this->model->getPrintOptions($_POST);
        $this->model->getDTContractors($_POST, $type, $print);
    }

    function action_add()
    {
        $this->getAccess($this->page, 'ch');
        $type = isset($_GET['type']) ? $_GET['type'] : false;
        $name = isset($_POST['name']) ? $_POST['name'] : false;
        if (!$type)
            return false;

        $this->model->addNewContractor($type, $name);
    }

    function action_delete()
    {
        $this->getAccess($this->page, 'd');
        $type = isset($_GET['type']) ? $_GET['type'] : false;
        $id = isset($_GET['id']) ? $_GET['id'] : false;
        if ($type || $id) {
            $this->model->deleteContractor($type, $id);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}