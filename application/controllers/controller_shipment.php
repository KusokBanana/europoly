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
        $print = isset($_GET['print']) ? $_GET['print'] : false;
        if ($print) {
            $print = [
                'visible' => isset($_GET['visible']) && $_GET['visible'] ? json_decode($_GET['visible'], true) : [],
                'selected' => isset($_GET['selected']) && $_GET['selected'] ? json_decode($_GET['selected'], true) : [],
                'filters' => isset($_GET['filters']) && $_GET['filters'] ? json_decode($_GET['filters'], true) : [],
            ];
        }

        $this->model->getDTSuppliersOrders($_POST, $print);
    }

    function action_dt_trucks_reduce()
    {
        $this->model->getDTSuppliersOrders($_POST, false, true);
    }
}