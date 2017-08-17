<?php

class ControllerAccountant extends Controller
{

    public $page = 'accountant';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelAccountant();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    function action_parser()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        date_default_timezone_set('Europe/London');
//        set_time_limit(-1);
//	    ini_set('memory_limit', '-1');
	    ini_set('max_execution_time', '9000');
//        $parser = [];
//        require dirname(__FILE__) . "/../../assets/phpExcel/Examples/parser.php";

//        $file = dirname(__FILE__) . "/../../dump_db/dumps/catalogue/total_dump_14_08.txt";

//        $fd = fopen($file, 'r') or die("не удалось прочитать файл");
//        $fd = fopen($file, 'w') or die("не удалось создать файл");
//	    echo count($parser);
//	    print_r($parser);
//	    $str = json_encode($parser);
//	    $parser = fread($fd, filesize($file));
//        fwrite($fd, $str);
//        fclose($fd);
//	    die();
//	    $parser = json_decode($parser, true);
//        echo '<br><br>Job Is Done';
//        die();
//        require dirname(__FILE__) . "/../../assets/phpExcel/Examples/expenses_parser.php";

//        $this->model->initCatalogueParser($parser, false);

//        $this->model->initParser($parser);

    }

    function action_index($action_param = null, $action_data = null)
    {

        $this->getAccess($this->page, 'v');

        $this->view->title = 'Payments';
        $roles = new Roles();

        $this->view->generalTable = $this->model->getTableData();

        $this->view->access = $roles->getPageAccessAbilities($this->page);
        $this->view->balance = $this->model->getBalanceData();

        $this->view->build('templates/template.php', 'accountant.php');
    }

    function action_monthly()
    {
        $this->page = 'accountant';
        $this->getAccess($this->page, 'v');
        $this->view->title = 'Monthly Payments';
        $this->view->monthly_payment = true;

        $this->view->generalTable = $this->model->getTableData('monthly');

        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);
        $this->view->balance = $this->model->getBalanceData();

        $this->view->build('templates/template.php', 'accountant.php');
    }

    function action_dt_payments()
    {
	    $print = $this->model->getPrintOptions($_POST);
	    $type = Helper::arrGetVal($_GET, 'type');
	    $_POST['type'] = $type;
        $this->model->getDTPayments($_POST, $print);
    }

    function action_dt_order_payments()
    {
    	$orderId = $_POST['products']['order_id'];
    	$type = $_POST['products']['type'];
	    $print = $this->model->getPrintOptions($_POST);
	    $this->model->getDTOrderPayments($orderId, $type, $_POST, $print);
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
    function action_dt_contractor_payments()
    {
	    $print = $this->model->getPrintOptions($_GET);

        $this->model->getDTPayments($_GET, $print);

    }

    function action_get_balance()
    {

        $begin = Helper::arrGetVal($_GET, 'begin');
        $end = Helper::arrGetVal($_GET, 'end');

        $balances = $this->model->getBalanceData($begin, $end);

        include '/application/views/templates/_balance.php';

    }

    function action_download_sberbank()
    {
        $file = isset($_FILES['sberbank_file']) && !empty($_FILES['sberbank_file']) ? $_FILES['sberbank_file'] : false;
        if ($file)
            $this->model->addPaymentFromFile($file);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}