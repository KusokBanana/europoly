<?php

class ControllerManagers_orders extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelManagers_orders();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Managers' Orders";
        $this->view->column_names = $this->model->managers_orders_column_names;
        $this->view->column_names_reduced = $this->model->managers_orders_reduced_column_names;
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
