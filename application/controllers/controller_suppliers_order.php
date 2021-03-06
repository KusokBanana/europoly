<?php

class ControllerSuppliers_order extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSuppliers_order();
        parent::afterConstruct();
        $this->model->page = $this->page;
    }

    public $page = 'suppliersOrder';

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->order = $this->model->getOrder($_GET['id']);
        if (!$this->view->order)
            $this->notFound();

        $this->view->title = "Supplier Order #".$this->view->order['order_id'];
        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);
        if ($this->view->access['p']) {
            $this->view->documents = $this->model->getDocuments($_GET['id']);
        }
        $this->view->itemsTable = $this->model->getTableData('general', ['order_id' => $_GET['id']]);
	    $this->view->paymentsTable = $this->model->getTableData('orders_payments', ['order_id' => $_GET['id']]);

        $this->view->modal_catalogue = $this->model->getTableData('modal_catalogue');

        $this->view->clients = $this->model->getClientsIdName();
        $this->view->sums = $this->model->getSums($_GET['id']);
        $this->view->status = $this->model->getOrderStatus($_GET['id']);
        $this->view->statusList = $this->model->getStatusList();
        $this->view->supplier = $this->model->getSupplier($this->view->order['supplier_id']);
        $this->view->build('templates/template.php', 'single_suppliers_order.php');
    }

    function action_dt_order_items()
    {
	    $print = $this->model->getPrintOptions($_POST);
	    $this->model->getDTOrderItems($_POST['products']['order_id'], $_POST, $print);
    }

    function action_change_status()
    {
        $this->getAccess($this->page, 'ch');
        $this->model->changeStatus($this->escape_and_empty_to_null($_GET['order_id']),
            $this->escape_and_empty_to_null($_GET['status']));
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    function action_delete_commission_agent()
    {
        $this->getAccess($this->page, 'd');
        $this->model->deleteCommissionAgent($this->escape_and_empty_to_null($_GET['order_id']));
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

    function action_delete_from_reserve()
    {
        $this->getAccess($this->page, 'd');
        if (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) {
            $order_item_id = $_GET["order_item_id"];
            $this->model->deleteFromReserve($order_item_id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    function action_print()
    {
        if (isset($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $result = $this->model->printDoc($orderId);
            echo $result;
        }
    }
}