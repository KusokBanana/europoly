<?php

class ControllerSuppliers_orders extends Controller
{

    public $page = 'suppliersOrders';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSuppliers_orders();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Suppliers' Orders";
        $roles = new Roles();
//        $this->view->column_names = $roles->returnModelNames($this->model->suppliers_orders_column_names, $this->page);
//        $this->view->column_names_reduce = $this->model->suppliers_orders_column_names_reduce;
        $this->view->access = $roles->returnAccessAbilities($this->page, 'ch');

        $this->view->tableNames = $this->model->tableNames;
        $this->view->column_names = $this->model->getColumns($this->model->suppliers_orders_column_names,
            $this->page, $this->model->tableNames[0], true);
        $this->view->column_names_reduced = $this->model->getColumns($this->model->suppliers_orders_column_names_reduce,
            $this->page, $this->model->tableNames[1], true);

        $this->view->originalColumns = $roles->returnModelNames($this->model->suppliers_orders_column_names, $this->page);
        $this->view->originalColumnsReduced = $roles->returnModelNames($this->model->suppliers_orders_column_names_reduce, $this->page);

        $array = $this->model->getSelects();
        $selects = $array['selects'];
        $rows = $array['rows'];
        $this->view->selects = $selects;
        $this->view->rows = $rows;

        $this->view->build('templates/template.php', 'suppliers_orders.php');
    }

    function action_dt_suppliers_orders()
    {
        $this->model->getDTSuppliersOrders($_GET);
    }

    function action_dt_suppliers_orders_reduce()
    {
        $this->model->getDTSuppliersOrdersReduce($_GET);
    }

    function action_add_suppliers_order()
    {
        $this->getAccess($this->page, 'ch');
        if (!empty($_POST)) {
            $products = $_POST['suppliers_products'];
            if (empty($products))
                return false;
            $suppliersOrderId = $_POST['suppliers_order_id'];

            $suppliersOrder = $this->model->addOrderItem($products, $suppliersOrderId);
            $location = ($suppliersOrder) ? '/suppliers_order?id='.$suppliersOrder : $_SERVER['HTTP_REFERER'];
            header("Location: " . $location);
        }
    }

    function action_get_active_suppliers_orders()
    {
        $orders = $this->model->getActiveSuppliersOrders();
        echo json_encode($orders);
        return;
    }

    function action_dt_suppliers_to_truck()
    {
        $products = [];
        if (isset($_GET['products']))
            $products = json_decode($_GET['products'], true);
        $this->model->getDTSuppliersOrdersToTruck($this->model->suppliers_orders_column_names, $products);
    }

}
