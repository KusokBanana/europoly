<?php

class ControllerDelivery_notes extends Controller
{
    public $page = 'deliveryNotes';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelDelivery_notes();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Delivery Notes";
        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $this->view->itemsTable = $this->model->getTableData();
        $this->view->ordersTable = $this->model->getTableData('reduced');

        $this->view->build('templates/template.php', 'delivery_notes.php');
    }

    function action_dt()
    {
	    $isReduced = (isset($_GET['type']) && $_GET['type'] === 'reduced') ? true : false;
	    $print = $this->model->getPrintOptions($_POST);

        $this->model->getDt($_POST, $print, $isReduced);
    }

    function action_view()
    {
        $this->getAccess($this->page, 'v');

        $note = $this->model->getDeliveryNote($_GET['id']);

        if (!$note) {
            $this->notFound();
        }

        $this->view->note = $note;
        $order = $this->model->getOrder($note['order_id']);
        if (!$order) {
            $this->notFound();
        }

        $this->view->order = $order;
        $this->view->legalEntityName = $this->model->getLegalEntityName($order['legal_entity_id']);
        $this->view->client = $this->model->getClient($order['client_id']);
        $this->view->commission_agent = $this->model->getClient($order["commission_agent_id"]);
        $this->view->sales_manager = $this->model->getUser($order["sales_manager_id"]);
        $this->view->title = "Delivery Note №" . $note['id'];
        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $orderItems = $this->model->getProducts($order['order_id'], $note['id']);
        $this->view->items = $orderItems;

	    $this->view->itemsTable = $this->model->getTableData('single', ['note_id' => $note['id']]);
	    $this->view->deliveryNotesModalTable = $this->model->getTableData('modal', ['ids' => $orderItems]);

	    if ($this->view->access['p']) {
            $this->view->documents = $this->model->getDocuments($_GET['id']);
        }

        $this->view->build('templates/template.php', 'single_delivery_note.php');
    }

    function action_get_dt_note()
    {
	    $arr = isset($_GET['isGet']) ? $_GET : $_POST;
	    $note_id = Helper::arrGetVal($arr, 'note_id');
	    $ids = Helper::arrGetVal($arr, 'ids');
	    $type = Helper::arrGetVal($arr, 'type', 'single');
	    $print = $this->model->getPrintOptions($_POST);
	    $this->model->getDTOrderItems($_POST, ['ids' => $ids, 'note_id' => $note_id, 'type' => $type], $print);
    }

    function action_add_order_item()
    {
        $this->getAccess($this->page, 'ch');
        $items = isset($_POST['product_ids']) ? $_POST['product_ids'] : false;
        $note_id = isset($_POST['note_id']) ? $_POST['note_id'] : false;
        $this->model->add($items, $note_id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_issue()
    {
        $this->getAccess($this->page, 'ch');
        $note_id = isset($_GET['note_id']) ? $_GET['note_id'] : false;
        if ($note_id) {
            $this->model->issue($note_id);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    function action_delete_item()
    {

        $item_id = (isset($_GET['item_id']) && $_GET['item_id']) ? $_GET['item_id'] : false;
        if ($item_id) {
            $this->model->deleteItem($item_id);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    function action_print_doc()
    {
        if (isset($_GET['note_id'])) {
            $note_id = $_GET['note_id'];
            $result = $this->model->printDoc($note_id, []);
            echo $result;
        }
    }

    function action_dt_for_order()
    {
        $order_id = isset($_POST['products']['order_id']) ? $_POST['products']['order_id'] : false;
	    $print = $this->model->getPrintOptions($_POST);
	    $this->model->getDTForOrder($order_id, $_POST, $print);
    }


}