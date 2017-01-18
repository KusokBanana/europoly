<?php

class ControllerAccountant extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelAccountant();
    }

    function action_parser()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        date_default_timezone_set('Europe/London');
        set_time_limit(99999999999);

        $parser = [];

//        require dirname(__FILE__) . "/../../assets/phpExcel/Examples/parser.php";

//        require dirname(__FILE__) . "/../../assets/phpExcel/Examples/expenses_parser.php";

//        $this->model->initCatalogueParser($parser);

//        $this->model->initParser($parser);

    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->column_names = $this->model->payments_column_names;
        $this->view->build('templates/template.php', 'accountant.php');
    }

    function action_dt_payments()
    {
        $this->model->getDTPayments($_GET);
    }

    function action_dt_order_payments()
    {
        $this->model->getDTOrderPayments($_GET['order_id'], $_GET['type'], $_GET);
    }

    function action_delete()
    {
        $payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : false;
        if (!$payment_id)
            return false;
        $this->model->deletePayment($payment_id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}