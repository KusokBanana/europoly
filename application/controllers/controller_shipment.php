<?php

class ControllerShipment extends Controller
{

    public $page = 'shipment';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelShipment();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Shipment";

        $this->view->itemsTable = $this->model->getTableData();
        $this->view->ordersTable = $this->model->getTableData('reduced');

        $this->view->build('templates/template.php', 'trucks.php');
    }

    function action_dt_trucks()
    {
	    $isReduced = (isset($_GET['type']) && $_GET['type'] === 'reduced') ? true : false;
	    $print = $this->model->getPrintOptions($_POST);

        $this->model->getDTSuppliersOrders($_POST, $print, $isReduced);
    }
}