<?php

class ControllerSuppliers_orders extends Controller
{

    public $page = 'suppliersOrders';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSuppliers_orders();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Suppliers' Orders";
        $roles = new Roles();
        $this->view->access = $roles->returnAccessAbilities($this->page, 'ch');

        $this->view->itemsTable = $this->model->getTableData();
        $this->view->ordersTable = $this->model->getTableData('reduced');

        $this->view->suppliers = $this->model->getSuppliers();
        $this->view->trucks = $this->model->getActiveTrucks();

        $this->view->build('templates/template.php', 'suppliers_orders.php');
    }

    function action_dt_suppliers_orders()
    {
	    $isReduced = (isset($_GET['type']) && $_GET['type'] === 'reduced') ? true : false;
	    $print = $this->model->getPrintOptions($_POST);

        $this->model->getDTSuppliersOrders($_POST, $print, $isReduced);
    }

    function action_add_empty_supplier_order()
    {
        $this->getAccess($this->page, 'ch');
        $supplierId = isset($_POST['supplier']) ? $_POST['supplier'] : false;

        $newId = $this->model->addNewOrder($supplierId);
        $location = ($newId) ? '/suppliers_order?id='.$newId : $_SERVER['HTTP_REFERER'];
        header("Location: " . $location);
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

    function action_load_into_truck()
    {
        $action_id = $_GET['action_id'];

        switch ($action_id) {
            case 1:
                $ids = Helper::arrGetVal($_GET, 'ids');
                echo $this->model->getItems($ids);
                return true;
            case 2:
                $truck_id = Helper::arrGetVal($_POST, 'truck_id');
                $amounts = Helper::arrGetVal($_POST, 'amounts');
                $resTruckId = $this->model->loadIntoTruck($amounts, $truck_id);
                $location = ($resTruckId) ? '/truck?id='.$resTruckId : $_SERVER['HTTP_REFERER'];
                header("Location: " . $location);
        }

    }

}
