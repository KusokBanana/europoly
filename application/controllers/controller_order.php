<?php

class ControllerOrder extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelOrder();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->order = $this->model->getOrder($_GET['id']);
        $this->view->order_status = $this->model->getItemStatusName($this->view->order['order_status_id']);
        $this->view->client = $this->model->getClient($this->view->order['client_id']);
        $this->view->sales_manager = $this->model->getUser($this->view->order["sales_manager_id"]);
        $this->view->commission_agent = $this->model->getClient($this->view->order["commission_agent_id"]);
        $this->view->title = 'Order #' . $this->view->order['order_id'] . ' / ' . $this->view->order['order_items_count'];
        $this->view->full_product_column_names = $this->model->full_product_column_names;
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;
        $this->view->managers = $this->model->getSalesManagersIdName();
//        $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
//        $this->view->clients = $this->model->getClientsIdName();
        $this->view->clients = $this->model->getClientsOfManager($this->view->order["sales_manager_id"]);
        $this->view->commission_agents = $this->model->getCommissionAgentsOfManager($this->view->order["sales_manager_id"]);
        $this->view->statusList = $this->model->getStatusList();
        $this->view->build('templates/template.php', 'single_order.php');
    }

    function action_dt_order_items()
    {
        $this->model->getDTOrderItems($_GET['order_id'], $_GET);
    }

    function action_send_to_logist()
    {
        $order_item_id = isset($_GET['order_item_id']) ? intval($_GET['order_item_id']) : 0;
        if (!$order_item_id)
            return false;
        $this->model->updateItemField($order_item_id, 'status_id', 3);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_cancel_order()
    {
        $this->model->cancelOrder($this->escape_and_empty_to_null($_POST['order_id']),
            $this->escape_and_empty_to_null($_POST['cancel_reason']));
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

    function action_change_contractor_id_to_name()
    {
        $tableAndId = (isset($_GET["tableAndId"]) && $_GET["tableAndId"]) ? $_GET["tableAndId"] : false;
        if ($tableAndId) {
            $arr = explode('.', $tableAndId);
            $table = $arr[0];
            $idName = $arr[1];
            $id = $arr[2];
            $contractor = $this->model->getFirst("SELECT `name` FROM $table WHERE `$idName` = $id");
            echo $contractor['name'];
            return;
        }
    }

    function action_hold()
    {
        $itemId = (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) ? intval($_GET["order_item_id"]) : false;
        if (!$itemId)
            return;
        $this->model->updateItemField($itemId, 'status_id', 2);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_issue()
    {
        $itemId = (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) ? intval($_GET["order_item_id"]) : false;
        if (!$itemId)
            return;
        $this->model->updateItemField($itemId, 'status_id', 10);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_reserve()
    {
        $itemId = (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) ? intval($_GET["order_item_id"]) : false;
        $action = (isset($_GET["action"]) && $_GET["action"]) ? $_GET["action"] : false;
        if (!$itemId || !$action)
            return false;
        if ($action == 'get_info') {
            $reserveInfo = $this->model->getReserveInformation($itemId);
            if ($reserveInfo)
                echo $reserveInfo;
            else
                return false;
        }
        if ($action == 'reserve') {

            $reserved_item_id = (isset($_GET["reserved_item_id"]) && $_GET["reserved_item_id"]) ?
                intval($_GET["reserved_item_id"]) : false;
            $type = (isset($_GET["type"]) && $_GET["type"]) ? $_GET["type"] : false;
            $this->model->reserve($itemId, $reserved_item_id, $type);
            header("Location: " . $_SERVER['HTTP_REFERER']);

        }
    }

}