<?php

class ControllerManagers_orders extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelManagers_orders();
    }

    public $page = 'managersOrders';

    function action_index($action_param = null, $action_data = null)
    {

        $this->getAccess($this->page, 'v');

        $this->view->title = "Managers' Orders";

        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $this->view->tableNames = $this->model->tableNames;
        $this->view->column_names = $this->model->getColumns($this->model->managers_orders_column_names,
            $this->page, $this->model->tableNames[0], true);
        $this->view->column_names_reduced = $this->model->getColumns($this->model->managers_orders_reduced_column_names,
            $this->page, $this->model->tableNames[1], true);

        $this->view->originalColumns = $roles->returnModelNames($this->model->managers_orders_column_names, $this->page);
        $this->view->originalColumnsReduced = $roles->returnModelNames($this->model->managers_orders_reduced_column_names, $this->page);
        $this->view->managers = $this->model->getSalesManagersIdName();
        $this->view->clients = $this->model->getClientsOfSalesManager($_SESSION['user_id']);

        $cache = new Cache();
        $selectsCache = $cache->read('managers_orders_selects');
        if (!empty($selectsCache)) {
            $array = $selectsCache;
            $selects = $array['selects'];
            $rows = $array['rows'];
        } else {
            $array = $this->model->getSelects();
            $selects = $array['selects'];
            $rows = $array['rows'];
            $cache->write('managers_orders_selects', $array);
        }
        $this->view->selects = $selects;
        $this->view->rows = $rows;

        $this->view->build('templates/template.php', 'managers_orders.php');
    }

    function action_dt_managers_orders()
    {
        $this->model->getDTManagersOrders($_GET);
    }

    function action_dt_managers_orders_reduced()
    {
        $this->model->getDTManagersOrdersReduced($_GET);
    }

    function action_dt_managers_orders_to_suppliers()
    {
        $products = [];
        if (isset($_GET['products']))
            $products = json_decode($_GET['products'], true);
        $this->model->getDTManagersOrdersToSuppliersOrder($this->model->managers_orders_column_names, $products);
    }
}
