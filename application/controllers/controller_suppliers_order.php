<?php

class ControllerSuppliers_order extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSuppliers_order();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Suppliers Order";
        $this->view->order = $this->model->getOrder($_GET['id']);
//        $this->view->client = $this->model->getClient($this->view->order['client_id']);
        $this->view->title = "Supplier Order #".$this->view->order['order_id'];
        $this->view->full_product_column_names = $this->model->full_product_column_names;
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;
        $this->view->clients = $this->model->getClientsIdName();
        $this->view->status = $this->model->getOrderStatus($_GET['id']);
        $this->view->statusList = $this->model->getStatusList();
        $this->view->supplier = $this->model->getSupplier($this->view->order['supplier_id']);
        $this->view->build('templates/template.php', 'single_suppliers_order.php');
    }

    function action_dt_order_items()
    {
        $this->model->getDTOrderItems($_GET['order_id'], $_GET);
    }

    function action_change_status()
    {
        $this->model->changeStatus($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['status']));
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    function action_delete_commission_agent()
    {
        $this->model->deleteCommissionAgent($this->escape_and_empty_to_null($_GET['order_id']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_add_order_item()
    {
        $this->model->addOrderItem($this->escape_and_empty_to_null($_POST['order_id']),
            json_decode($_POST['product_ids']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_delete_order_item()
    {
        $this->model->deleteOrderItem($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['order_item_id']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_change_field()
    {
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

    function action_delete_from_reserve()
    {
        if (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) {
            $order_item_id = $_GET["order_item_id"];
            $this->model->deleteFromReserve($order_item_id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

    }
}