<?php

class ControllerTruck extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelTruck();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    public $page = 'truck';

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Truck #" . $_GET['id'];
        $this->view->order = $this->model->getOrder($_GET['id']);
        if (!$this->view->order)
            $this->notFound();


        $roles = new Roles();
//        $this->view->suppliers_orders_column_names =
//            $roles->returnModelNames($this->model->suppliers_orders_column_names, 'suppliersOrders');
        $this->view->column_names = $roles->returnModelNames($this->model->truck_column_names, $this->page);
//        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;
        $this->view->modal_suppliers_items = $this->model->getTableData('modal_suppliers_orders');

        $this->view->itemsTable = $this->model->getTableData('general', ['truck_id' => $_GET['id']]);

        $this->view->status = $this->model->getTruckStatus($_GET['id']);
        $this->view->statusList = $this->model->getStatusList();
        $this->view->delivery = $this->model->getDelivery($_GET['id']);
        $this->view->customs = $this->model->getCustoms($_GET['id']);
        $this->view->sums = $this->model->getSums($_GET['id']);
        $this->view->warehouses = $this->model->getWarehousesIdNames();

        $this->view->access = $roles->getPageAccessAbilities($this->page);
        if ($this->view->access['p']) {
            $this->view->documents = $this->model->getDocuments($_GET['id']);
        }

        $this->view->build('templates/template.php', 'single_truck.php');
    }

    function action_add_order_in_truck()
    {
        if (!empty($_POST)) {
            $products = $_POST['truck_products'];
            if (empty($products))
                return false;
            $suppliersOrderId = $_POST['truck_id'];

            $suppliersOrder = $this->model->addTruckItem($products, $suppliersOrderId);
            $location = ($suppliersOrder) ? '/truck?id='.$suppliersOrder : $_SERVER['HTTP_REFERER'];
            header("Location: " . $location);
        }
    }

    function action_dt_suppliers_items()
    {
        $this->model->getDTSuppliersOrdersToTruck($_POST);
    }

    function action_put_to_the_warehouse()
    {
        $this->getAccess($this->page, 'ch');
        $truck_id = Helper::arrGetVal($_GET, 'truck_id');
        $truck_item_id = Helper::arrGetVal($_GET, 'truck_item_id');
        $action_id = $_GET['action_id'];

        switch ($action_id) {
            case 1:
                $items = $this->model->getItemsToPutToWarehouse($truck_id, $truck_item_id);
                echo $items;
                return true;
            case 2:
                $amounts = Helper::arrGetVal($_POST, 'amounts');
                $warehouse_id = Helper::arrGetVal($_POST, 'warehouse_id');
                $this->model->putToTheWarehouse($amounts, $warehouse_id);
                header("Location: " . $_SERVER['HTTP_REFERER']);
        }

    }

    function action_print_doc()
    {
        if (isset($_GET["truck_id"])) {
            $truck_id = $_GET['truck_id'];
            $result = $this->model->printDoc($truck_id);
            echo $result;
        }

        }

    function action_put_item_to_warehouse()
    {
        $this->getAccess($this->page, 'ch');
        $warehouse_id = $_POST['warehouse_id'];
        $truck_item_id = $_GET['truck_item_id'];
        $this->model->putItemToWarehouse($truck_item_id, $warehouse_id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_dt_order_items()
    {
	    $print = $this->model->getPrintOptions($_POST);
	    $this->model->getDTTrucks($_POST['products']['truck_id'], $_POST, $print);
    }

    function action_change_status()
    {
        $this->getAccess($this->page, 'ch');
        $this->model->changeStatus($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['status']));
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    function action_add_order_item()
    {
        $this->getAccess($this->page, 'ch');
        $this->model->addOrderItem($this->escape_and_empty_to_null($_POST['order_id']),
            json_decode($_POST['product_ids']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_delete_order_item()
    {
        $this->getAccess($this->page, 'd');
        $this->model->deleteOrderItem($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['order_item_id']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_change_field()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_POST["pk"]) && isset($_POST["name"]) && isset($_POST["value"])) {
            $order_id = intval($_POST["pk"]);
            $name = $this->model->escape_string($_POST["name"]);
            $value = $this->model->escape_string($_POST["value"]);
            if (!$this->model->updateField($order_id, $name, $value)) {
                http_response_code(500);
            } else {
                echo $value;
            }
        } else {
            http_response_code(400);
        }
    }

    function action_change_item_field()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_POST["pk"]) && isset($_POST["name"]) && isset($_POST["value"])) {
            $order_item_id = intval($_POST["pk"]);
            $name = $this->model->escape_string($_POST["name"]);
            $value = $this->model->escape_string($_POST["value"]);

            if (!$this->model->updateItemField($order_item_id, $name, $value)) {
                http_response_code(500);
            } else {
                echo $value;
            }
        } else {
            http_response_code(400);
        }
    }

    function action_change_truck_select()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_POST["pk"])){
            $transportation_company_id = isset($_POST["transportation_company_id"]) && $_POST["transportation_company_id"] ?
                $this->model->escape_string($_POST["transportation_company_id"]) : false;
            $custom_id = isset($_POST["custom_id"]) && $_POST["custom_id"] ?
                $this->model->escape_string($_POST["custom_id"]) : false;
            $truck_id = intval($_POST["pk"]);

            if ($transportation_company_id)
                $this->model->updateField($truck_id, 'transportation_company_id', $transportation_company_id);

            if ($custom_id)
                $this->model->updateField($truck_id, 'custom_id', $custom_id);

            header("Location: " . $_SERVER['HTTP_REFERER']);

        } else {
            http_response_code(400);
        }
    }

}