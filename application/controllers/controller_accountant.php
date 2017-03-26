<?php

use PhpOffice\PhpWord\TemplateProcessor;

class ControllerAccountant extends Controller
{

    public $page = 'accountant';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelAccountant();
        parent::afterConstruct();
    }

    function action_parser()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        date_default_timezone_set('Europe/London');
        set_time_limit(-1);

        $parser = [];

        require dirname(__FILE__) . "/../../assets/phpExcel/Examples/parser.php";

//        require dirname(__FILE__) . "/../../assets/phpExcel/Examples/expenses_parser.php";

        $this->model->initCatalogueParser($parser);

//        $this->model->initParser($parser);

    }


    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');

        $this->view->title = 'Accountant';
        $this->view->tableName = $this->model->tableName;
        $this->view->column_names = $this->model->getColumns($this->model->payments_column_names,
            $this->page, $this->model->tableName, true);
        $roles = new Roles();
        $this->view->originalColumns = $roles->returnModelNames($this->model->payments_column_names, $this->page);

        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $array = $this->model->getSelects();
        $selects = $array['selects'];
        $rows = $array['rows'];

        $this->view->selects = $selects;
        $this->view->rows = $rows;

        $this->view->build('templates/template.php', 'accountant.php');
    }

    function action_monthly()
    {
        $this->page = 'accountant';
        $this->getAccess($this->page, 'v');
        $this->view->title = 'Monthly Payments';
        $this->view->monthly_payment = true;

        $this->view->tableName = $this->model->tableName;
        $this->view->column_names = $this->model->getMonthlyPaymentsCols('name');
        $roles = new Roles();
        $this->view->originalColumns = $this->model->getMonthlyPaymentsCols('name', true);

        $this->view->access = $roles->getPageAccessAbilities($this->page);

        $array = $this->model->getSelects(true);
        $selects = $array['selects'];
        $rows = $array['rows'];

        $this->view->selects = $selects;
        $this->view->rows = $rows;

        $this->view->build('templates/template.php', 'accountant.php');
    }

    function action_dt_payments()
    {
        $print = isset($_GET['print']) ? $_GET['print'] : false;
        if ($print) {
            $print = [
                'visible' => isset($_GET['visible']) && $_GET['visible'] ? json_decode($_GET['visible'], true) : [],
                'selected' => isset($_GET['selected']) && $_GET['selected'] ? json_decode($_GET['selected'], true) : [],
                'filters' => isset($_GET['filters']) && $_GET['filters'] ? json_decode($_GET['filters'], true) : [],
            ];
        }

        $this->model->getDTPayments($_GET, $print);
    }

    function action_dt_order_payments()
    {
        $this->model->getDTOrderPayments($_GET['order_id'], $_GET['type'], $_GET);
    }

    function action_delete()
    {
        $this->getAccess($this->page, 'd');
        $payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : false;
        if (!$payment_id)
            return false;
        $this->model->deletePayment($payment_id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_similar_payment()
    {

        $this->getAccess($this->page, 'ch');
        $payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : false;
        if (!$payment_id)
            return false;
        $payment = $this->model->getPayment($payment_id);
        if ($payment) {
            unset($payment['payment_id']);
            $payment['responsible_person_id'] = $_SESSION['user_id'];
            $payment['date'] = date('Y-m-d');
            echo json_encode($payment);
        }
        echo false;
    }
}