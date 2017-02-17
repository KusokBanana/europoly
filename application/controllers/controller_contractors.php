<?php

class ControllerContractors extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelContractors();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess('contractors', 'v');
        $this->view->title = "Contractors";
        $this->view->clients_column_names = $this->model->client_column_names;
        $this->view->column_names = $this->model->column_names;
        $this->view->managers = $this->model->getSalesManagersIdName();
        $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
        $this->view->build('templates/template.php', 'contractors.php');
    }

    function action_dt_suppliers()
    {
        $this->model->getDTSuppliers($_GET);
    }

    function action_dt_customs()
    {
        $this->model->getDTCustoms($_GET);
    }

    function action_dt_transportation()
    {
        $this->model->getDTTransportation($_GET);
    }

    function action_dt_other()
    {
        $this->model->getDTOther($_GET);
    }

    function action_add()
    {
        $this->getAccess('contractors', 'ch');
        $type = isset($_GET['type']) ? $_GET['type'] : false;
        $name = isset($_POST['name']) ? $_POST['name'] : false;
        if (!$type)
            return false;

        $this->model->addNewContractor($type, $name);
    }

    function action_delete()
    {
        $this->getAccess('contractors', 'd');
        $type = isset($_GET['type']) ? $_GET['type'] : false;
        $id = isset($_GET['id']) ? $_GET['id'] : false;
        if ($type || $id) {
            $this->model->deleteContractor($type, $id);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}