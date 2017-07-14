<?php

class ControllerManagers_orders extends Controller
{
    public $page = 'managersOrders';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelManagers_orders();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }


    function action_index($action_param = null, $action_data = null)
    {

        $this->getAccess($this->page, 'v');

        $this->view->title = "Managers' Orders";

        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $this->view->itemsTable = $this->model->getTableData();
        $this->view->ordersTable = $this->model->getTableData('reduced');

        $withAdmins = $this->user->role_id == ROLE_ADMIN || $this->user->role_id == ROLE_OPERATING_MANAGER ? true : false;
        $this->view->managers = $this->model->getSalesManagersIdName($withAdmins);
        $clientsFor = $this->user->role_id == ROLE_ADMIN || $this->user->role_id == ROLE_OPERATING_MANAGER ? false : $this->user->user_id;
        $this->view->clients = $this->model->getClientsOfSalesManager($clientsFor);

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
        $this->model->getDTManagersOrders($_POST, false, true);
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
