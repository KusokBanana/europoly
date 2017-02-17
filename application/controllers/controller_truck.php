<?php

class ControllerTruck extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelTruck();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess('truck', 'v');
        $this->view->title = "Truck #" . $_GET['id'];
        $this->view->order = $this->model->getOrder($_GET['id']);
        $roles = new Roles();
        $this->view->suppliers_orders_column_names =
            $roles->returnModelNames($this->model->suppliers_orders_column_names, 'suppliersOrders');
        $this->view->column_names = $roles->returnModelNames($this->model->truck_column_names, 'truck');
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;
        $this->view->status = $this->model->getTruckStatus($_GET['id']);
        $this->view->statusList = $this->model->getStatusList();
        $this->view->delivery = $this->model->getDelivery($_GET['id']);
        $this->view->customs = $this->model->getCustoms($_GET['id']);
        $this->view->sums = $this->model->getSums($_GET['id']);
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

    function action_put_to_the_warehouse()
    {
        $this->getAccess('truck', 'ch');
        $this->model->putToTheWarehouse($_GET['truck_id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_put_item_to_warehouse()
    {
        $this->getAccess('truck', 'ch');
        $warehouseId = $this->model->putItemToWarehouse($_GET['truck_item_id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_get_active_trucks()
    {
        $orders = $this->model->getActiveTrucks();
        echo json_encode($orders);
        return;
    }

    function action_dt_order_items()
    {
        $this->model->getDTTrucks($_GET['order_id'], $_GET);
    }

    function action_change_status()
    {
        $this->getAccess('truck', 'ch');
        $this->model->changeStatus($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['status']));
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    function action_add_order_item()
    {
        $this->getAccess('truck', 'ch');
        $this->model->addOrderItem($this->escape_and_empty_to_null($_POST['order_id']),
            json_decode($_POST['product_ids']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_delete_order_item()
    {
        $this->getAccess('truck', 'd');
        $this->model->deleteOrderItem($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['order_item_id']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_change_field()
    {
        $this->getAccess('truck', 'ch');
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
        $this->getAccess('truck', 'ch');
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
        $this->getAccess('truck', 'ch');
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