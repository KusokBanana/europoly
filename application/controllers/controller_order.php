<?php

class ControllerOrder extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelOrder();
        parent::afterConstruct();
    }

    public $page = 'order';

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');

        $order = $this->model->getOrder($_GET['id']);

        if (!$order) {
            $this->notFound();
        }
        $this->view->order = $order;
        $this->view->client = $this->model->getClient($order['client_id']);

        $userId = $this->user->user_id;

        if (($_SESSION["user_role"] == ROLE_SALES_MANAGER &&
                ($order['sales_manager_id'] == $userId ||
                    $this->view->client['sales_manager_id'] == $userId ||
                    $this->view->client['operational_manager_id'] == $userId)
            ) || $_SESSION["perm"] >= OPERATING_MANAGER_PERM
        ) {

            $this->view->order_status = $this->model->getItemStatusName($order['order_status_id']);
            $this->view->sales_manager = $this->model->getUser($order["sales_manager_id"]);
            $this->view->commission_agent = $this->model->getClient($order["commission_agent_id"]);
            $this->view->title = 'Order #' . $order['order_id'];
            $this->view->warehouses = $this->model->getWarehousesIdNames();

            $roles = new Roles();
            $this->view->full_product_column_names = $this->model->getColumns($this->model->full_product_column_names,
                'catalogue', 'table_catalogue', true);
            $this->view->originalColumns = $roles->returnModelNames($this->model->full_product_column_names, 'catalogue');

            $this->view->column_names = $roles->returnModelNames($this->model->order_columns_names, $this->page);
            $this->view->access = $roles->getPageAccessAbilities($this->page);
            if ($this->view->access['p']) {
                $this->view->documents = $this->model->getDocuments($_GET['id']);
            }

            $cache = new Cache();
            $selectsCache = $cache->read('catalogue_selects');
            if (!empty($selectsCache)) {
                $array = $selectsCache;
                $selects = $array['selects'];
                $rows = $array['rows'];
            } else {
                $array = $this->model->getSelects();
                $selects = $array['selects'];
                $rows = $array['rows'];
                $cache->write('catalogue_selects', $array);
            }
            $this->view->selects = $selects;
            $this->view->rows = $rows;

            $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;
            $this->view->managers = $this->model->getSalesManagersIdName();
            $this->view->legalEntities = $this->model->getLegalEntities();
            $this->view->legalEntityName = $this->model->getLegalEntityName($order['legal_entity_id']);

            $clientsFor = $this->user->role_id == ROLE_ADMIN ? false : $order["sales_manager_id"];
            $this->view->clients = $this->model->getClientsOfSalesManager($clientsFor);
            $this->view->commission_agents = $this->model->getCommissionAgentsOfManager($order["sales_manager_id"]);
            $this->view->statusList = $this->model->getStatusList();
            $this->view->build('templates/template.php', 'single_order.php');
        } else {
            $this->getAccess('none', 'v');
        }
    }

    function action_parse()
    {
        $this->model->parse();
    }

    function action_dt_order_items()
    {
        $this->model->getDTOrderItems($_GET['order_id'], $_GET);
    }

    function action_send_to_logist()
    {
        $this->getAccess($this->page, 'ch');
        $order_item_ids = isset($_GET['order_item_ids']) ? $_GET['order_item_ids'] : 0;

        if ($order_item_ids) {
            $order_item_ids = explode(',', $order_item_ids);
            $json = null;
            foreach ($order_item_ids as $order_item) {
                $json = $this->model->updateItemField($order_item, 'status_id', SENT_TO_LOSIGT);
            }
            if (!is_null($json)) {
                echo $json;
            }
        }
    }

    function action_ship_to_customer()
    {
        $this->getAccess($this->page, 'ch');
        $actionId = isset($_GET['actionId']) ? $_GET['actionId'] : false;

        if ($actionId == 1) {
            $order_item_ids = isset($_GET['order_item_ids']) ? $_GET['order_item_ids'] : false;
            $items = $this->model->getItemsFromStringIds($order_item_ids);
            if (!is_array($items)) {
                echo $items;
                return 0;
            }
            $items = $this->model->shipToCustomerGetProducts($order_item_ids);
            echo json_encode($items);
            return 1;
        } elseif ($actionId == 2) {
            $ship = isset($_POST['ship']) ? $_POST['ship'] : false;
            $order_item_ids = isset($_POST['order_items_ids']) ? $_POST['order_items_ids'] : false;
            $json = $this->model->shipToCustomer($order_item_ids);
            if (!is_null($json)) {
                echo $json;
                return 0;
            }
            $this->model->shipToCustomer($ship);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

    }

    function action_split()
    {

        $order_item_id = isset($_GET['order_item_id']) ? $_GET['order_item_id'] : false;
        $actionId = isset($_GET['action_id']) ? $_GET['action_id'] : false;
        switch ($actionId) {
            case 1:
                $item = $this->model->getFirst("SELECT order_items.amount as amount, order_items.item_id, " .
                    "products.name as name, order_items.status_id as status_id " .
                    "FROM order_items " .
                    "LEFT JOIN products ON (order_items.product_id = products.product_id) " .
                    "WHERE item_id = $order_item_id");
                $status_id = $item['status_id'];
                if (intval($status_id) > ON_STOCK) {
                    echo json_encode(['success' => 0, 'message' => 'Item must have status less than On Stock!']);
                    return false;
                }
                echo json_encode($item);
                return true;
            case 2:
                $amount_1 = isset($_POST['amount_1']) ? $_POST['amount_1'] : false;
                $amount_2 = isset($_POST['amount_2']) ? $_POST['amount_2'] : false;
                $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : false;
                if ($amount_1 && $amount_2 && $item_id) {
                    $this->model->split($item_id, $amount_1);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                }
        }
    }

    function action_join()
    {

        $order_item_ids = isset($_GET['order_item_ids']) ? $_GET['order_item_ids'] : false;
        $actionId = isset($_GET['action_id']) ? $_GET['action_id'] : false;

        switch ($actionId) {
            case 1:
                $this->model->join($order_item_ids);
        }

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

    function action_hold()
    {
        $this->getAccess($this->page, 'ch');
        $itemId = (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) ? intval($_GET["order_item_id"]) : false;
        if (!$itemId)
            return;
        $this->model->updateItemField($itemId, 'status_id', HOLD);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_issue()
    {
        $this->getAccess($this->page, 'ch');
        $itemId = (isset($_GET["order_item_id"]) && $_GET["order_item_id"]) ? intval($_GET["order_item_id"]) : false;
        if (!$itemId)
            return;
        $this->model->updateItemField($itemId, 'status_id', EXPECTS_ISSUE);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_return_item()
    {
        $this->getAccess($this->page, 'ch');
        $itemId = (isset($_GET["item_id"]) && $_GET["item_id"]) ? intval($_GET["item_id"]) : false;
        if (!$itemId)
            return;
        $warehouse_id = (isset($_GET['warehouse_id'])) ? $_GET['warehouse_id'] : 1;
        $this->model->updateItemField($itemId, 'status_id', RETURNED);
        $this->model->updateItemField($itemId, 'warehouse_id', $warehouse_id);
        // here we can return only one item for once
        $this->model->addLog(LOG_RETURN_TO_WAREHOUSE, ['items' => [$itemId], 'warehouse_id' => $warehouse_id]);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_reserve()
    {
        $this->getAccess($this->page, 'ch');
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

    function action_validate_item_field()
    {
        $itemId = isset($_GET['item_id']) ? $_GET['item_id'] : false;
        $fieldName = isset($_GET['name']) ? $_GET['name'] : false;
        $value = isset($_GET['value']) ? $_GET['value'] : false;
        if ($itemId && $fieldName && $value) {
            echo $this->model->validateItemField($itemId, $fieldName, $value);
        }
    }

    function action_print_payment()
    {
        if (isset($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $type = $_GET['type'];
            $items = isset($_GET['items']) && $_GET['items'] ? $_GET['items'] : '';
            $result = $this->model->printDoc($orderId, $type, $items);
            echo $result;
        }
    }

//    function action_change_price()
//    {
//        $this->model->changePrice();
//    }

}