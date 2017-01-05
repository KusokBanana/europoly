<?php

class ControllerShipment extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelShipment();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Shipment";
        $this->view->column_names = $this->model->suppliers_orders_column_names;
        $this->view->column_names_reduce = $this->model->suppliers_orders_column_names_reduce;
        $this->view->build('templates/template.php', 'trucks.php');
    }

    function action_dt_trucks()
    {
        $this->model->getDTSuppliersOrders($_GET);
    }

    function action_dt_trucks_reduce()
    {
        $this->model->getDTSuppliersOrdersReduce($_GET);
    }
}