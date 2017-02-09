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

        //        $cache = new Cache();
//        $selectsCache = $cache->read('suppliers_orders_selects');
//        if (!empty($selectsCache)) {
//            $array = $selectsCache;
//            $selects = $array['selects'];
//            $rows = $array['rows'];
//        } else {
        $array = $this->model->getSelects();
        $selects = $array['selects'];
        $rows = $array['rows'];
//            $cache->write('suppliers_orders_selects', $array);
//        }
        $this->view->selects = $selects;
        $this->view->rows = $rows;

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