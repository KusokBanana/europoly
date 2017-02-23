<?php

class ControllerShipment extends Controller
{

    public $page = 'shipment';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelShipment();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Shipment";
//        $roles = new Roles();
//        $this->view->column_names = $roles->returnModelNames($this->model->suppliers_orders_column_names, $this->page);
//        $this->view->column_names_reduce = $this->model->suppliers_orders_column_names_reduce;

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
        $this->model->getDTSuppliersOrders($_GET);
    }

    function action_dt_trucks_reduce()
    {
        $this->model->getDTSuppliersOrdersReduce($_GET);
    }
}