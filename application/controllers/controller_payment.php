<?php

class ControllerPayment extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelPayment();
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if ($id = $_GET['id']) {
            $this->getAccess('payment', 'v');
            if ($id == 'new') {
                if (isset($_POST['Similar']) && !empty($_POST['Similar'])) {
                    $this->view->payment = $_POST['Similar'];
                    $this->view->post_order = true;
                }
                $this->view->title = 'New Payment';
                if (isset($_GET['type']) && $_GET['type'] == 'monthly' || (isset($this->view->payment)
                    && isset($this->view->payment['is_monthly']) && $this->view->payment['is_monthly'])) {
                    $this->view->payment['is_monthly'] = true;
                    $this->view->title = 'New Monthly Payment';
                }
            } else {
                $this->view->payment = $this->model->getPayment($id);
                if (!$this->view->payment)
                    $this->notFound();

                $this->view->title = $this->view->payment['payment_id'];
                $this->view->contractor = $this->model->getContractorName($this->view->payment['category'],
                    $this->view->payment['contractor_id']);


	            $this->view->title = ($this->view->payment['is_monthly'] ? 'Monthly ' : '') . 'Payment #' .
                    $this->view->payment['payment_id'] .
                    ($this->view->payment['direction'] == 'Income' ? ' to ' : ' from ') . $this->view->contractor;

	            if ($this->view->payment['order_id']) {
		            $order = $this->model->getOrder($this->view->payment['order_id']);
		            $this->view->title .= ' on order ' . ($order['visible_order_id'] ? $order['visible_order_id'] :
			            $order['order_id']);
	            }


            }

            $roles = new Roles();
            $this->view->access = $roles->getPageAccessAbilities('payment');
            if ($this->view->access['p']) {
                $this->view->documents = $this->model->getDocuments($_GET['id']);
            }

            $this->view->entities = $this->model->getLegalEntitiesIdName();
            $this->view->transfers = $this->model->getTransferTypesIdName();
            $this->view->managers = $this->model->getSalesManagersIdName(false);
            $this->view->clients = $this->model->getClientsIdName();
            $this->view->expenses = $this->model->getExpenses();
            $this->view->purpose = ($id != 'new') ? $this->model->getPurpose($this->view->payment) : '';
            $this->view->build('templates/template.php', 'payment.php');
        }
    }

    function action_get_select()
    {
        $category = isset($_POST['category']) ? $_POST['category'] : false;
        $contractor = isset($_POST['contractor']) ? $_POST['contractor'] : false;
        $select = isset($_POST['select']) ? $_POST['select'] : false;
        if (!$category || !$select) {
            echo false;
            return false;
        }

        if ($select == 'contractor' && !$contractor) {
            echo false;
            return false;
        }

        $return = $this->model->getSelectByCategory($category, $contractor);
        if ($return && is_array($return)) {
            echo json_encode($return);
            return true;
        }
        else {
            echo false;
            return false;
        }
    }

    function action_save_payment()
    {
        $this->getAccess('payment', 'ch');
        $payment_id = isset($_GET['id']) ? $_GET['id'] : false;
        $type = isset($_GET['type']) ? $_GET['type'] : false;
        $form = !empty($_POST) ? $_POST : false;
        if (!$payment_id || !$form)
            return false;

        $paymentId = $this->model->savePayment($form, $payment_id);
//        if ($paymentId && $payment_id == 'new') {
        if ($paymentId) {
//            header("Location: " . '/accountant' . ($type == 'monthly' ? "/$type" : ''));
	        header("Location: " . '/payment?id=' . $paymentId);
        }
        else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    function action_print_doc()
    {
        if (isset($_GET['payment_id'])) {
            $orderId = $_GET['payment_id'];
            $result = $this->model->printDoc($orderId);
            echo $result;
        }
    }

    function action_get_purpose()
    {

        if (isset($_POST['order_id']) && isset($_POST['category'])) {
            echo $this->model->getPurpose($_POST);
        }

    }

    function action_parse_cbr()
    {
        $this->model->cbrParser();
    }

    function action_get_currency()
    {
        if (isset($_POST['date']) && isset($_POST['currency'])) {
            echo $this->model->getOfficialCurrency($_POST['currency'], $_POST['date']);
        }
    }

}