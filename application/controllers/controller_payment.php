<?php

class ControllerPayment extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelPayment();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if ($id = $_GET['id']) {
            if ($id == 'new') {
                if (isset($_POST['Order']) && !empty(isset($_POST['Order']))) {
                    $this->view->payment = $_POST['Order'];
                    $this->view->post_order = true;
                }
                $this->view->title = 'New Payment';
            } else {
                $this->view->payment = $this->model->getPayment($id);
                $this->view->title = $this->view->payment['payment_id'];
                $this->view->contractor = $this->model->getContractorName($this->view->payment['category'],
                    $this->view->payment['contractor_id']);
            }
            $this->view->entities = $this->model->getLegalEntitiesIdName();
            $this->view->transfers = $this->model->getTransferTypesIdName();
            $this->view->managers = $this->model->getSalesManagersIdName();
            $this->view->clients = $this->model->getClientsIdName();
            $this->view->currentUser = $this->model->getUser($_SESSION["user_id"]);
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

        $payment_id = isset($_GET['id']) ? $_GET['id'] : false;
        $form = !empty($_POST) ? $_POST : false;
        if (!$payment_id || !$form)
            return false;

        $paymentId = $this->model->savePayment($form, $payment_id);
        if ($paymentId && $payment_id == 'new') {
            header("Location: " . '/payment?id='.$paymentId);
        }
        else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

}