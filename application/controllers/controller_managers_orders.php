<?php

class ControllerManagers_orders extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelManagers_orders();
        parent::afterConstruct();
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

        $withAdmins = $this->user->role_id == ROLE_ADMIN || $this->user->role_id == ROLE_OPERATING_MANAGER ? true : false;
        $this->view->managers = $this->model->getSalesManagersIdName($withAdmins);
        $clientsFor = $this->user->role_id == ROLE_ADMIN || $this->user->role_id == ROLE_OPERATING_MANAGER ? false : $this->user->user_id;
        $this->view->clients = $this->model->getClientsOfSalesManager($clientsFor);

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

        $selects = $this->model->getSelects(true);
        $this->view->reducedSelects = $selects['selects'];
        $this->view->reducedRows = $selects['rows'];

        $this->view->build('templates/template.php', 'managers_orders.php');
    }

    function action_dt_managers_orders()
    {

        $print = isset($_GET['print']) ? $_GET['print'] : false;
        if ($print) {
            $print = [
                'visible' => isset($_GET['visible']) && $_GET['visible'] ? json_decode($_GET['visible'], true) : [],
                'selected' => isset($_GET['selected']) && $_GET['selected'] ? json_decode($_GET['selected'], true) : [],
                'filters' => isset($_GET['filters']) && $_GET['filters'] ? json_decode($_GET['filters'], true) : [],
            ];
        }

        $this->model->getDTManagersOrders($_POST, $print);
    }

    function action_dt_managers_orders_reduced()
    {
        $this->model->getDTManagersOrdersReduced($_POST);
    }

    function action_dt_managers_orders_to_suppliers()
    {
        $products = [];
        if (isset($_GET['products']))
            $products = json_decode($_GET['products'], true);
        $this->model->getDTManagersOrdersToSuppliersOrder($this->model->managers_orders_column_names, $products);
    }

    function action_get_clients()
    {
        $managerId = isset($_GET['manager_id']) ? $_GET['manager_id'] : false;
        $clients = $this->model->getClientsOfSalesManager($managerId);
        echo json_encode($clients);
        return true;
    }
}
