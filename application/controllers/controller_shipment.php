<?php

class ControllerShipment extends Controller
{

    public $page = 'shipment';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelShipment();
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Shipment";

        $this->view->tableNames = $this->model->tableNames;
        $this->view->column_names = $this->model->getColumns($this->model->suppliers_orders_column_names,
            $this->page, $this->model->tableNames[0], true);
        $this->view->column_names_reduced = $this->model->getColumns($this->model->suppliers_orders_column_names_reduce,
            $this->page, $this->model->tableNames[1], true);

        $roles = new Roles();
        $this->view->originalColumns = $roles->returnModelNames($this->model->suppliers_orders_column_names, $this->page);
        $this->view->originalColumnsReduced = $roles->returnModelNames($this->model->suppliers_orders_column_names_reduce, $this->page);

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
        $print = isset($_GET['print']) ? $_GET['print'] : false;
        if ($print) {
            $print = [
                'visible' => isset($_GET['visible']) && $_GET['visible'] ? json_decode($_GET['visible'], true) : [],
                'selected' => isset($_GET['selected']) && $_GET['selected'] ? json_decode($_GET['selected'], true) : [],
                'filters' => isset($_GET['filters']) && $_GET['filters'] ? json_decode($_GET['filters'], true) : [],
            ];
        }

        $this->model->getDTSuppliersOrders($_GET, $print);
    }

    function action_dt_trucks_reduce()
    {
        $this->model->getDTSuppliersOrdersReduce($_GET);
    }
}