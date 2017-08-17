<?php

class ControllerContractor extends Controller
{
    public $page = 'contractor';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelContractor();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {

        $this->getAccess($this->page, 'v');
//        $this->view->payments_column_names = $this->model->getPaymentsColumnNames();

        $contractorId = isset($_GET['id']) ? $_GET['id'] : 0;
        $contractorType = isset($_GET['type']) ? $_GET['type'] : 0;
        $contractor = $this->model->getContractor($contractorId, $contractorType);

        if (!$contractor) {
            $this->notFound();
        } else {
            $this->view->contractor = $contractor;

            $this->view->title = 'Contractor ' . $contractor['name'];
            $this->view->isGoods = $this->model->isGoodsSearch($contractorType);
            $this->view->isServices = $this->model->isServicesSearch($contractorType);

            $this->setPaymentsValues($contractorId, $contractorType);
            $this->setGoodsValues($contractorId, $contractorType);
            $this->setServicesValues($contractorId, $contractorType);

            $paymentsRows = isset($this->view->generalTable['filterSearchValues']) ?
                $this->view->generalTable['filterSearchValues'] : [];
            $goodsRows = isset($this->view->tableGoods['filterSearchValues']) ?
                $this->view->tableGoods['filterSearchValues'] : [];
            $servicesRows = isset($this->view->tableServices['filterSearchValues']) ?
                $this->view->tableServices['filterSearchValues'] : [];

            $this->view->contractor_type = $contractorType;

            $this->view->balance = $this->model->getInformation($paymentsRows, $goodsRows, $servicesRows, $contractorType);

            $this->view->build('templates/template.php', 'single_contractor.php');
        }


    }

    public function setPaymentsValues($contractor_id, $contractor_type)
    {
        require_once dirname(__FILE__) . "/../models/model_accountant.php";
        $modelAccountant = new ModelAccountant();

        $modelAccountant->tableName = $this->model->tableNames[0];
        $this->view->generalTable = $modelAccountant->getTableData('contractor',
            ["payments.contractor_id = $contractor_id", "payments.category = '$contractor_type'"]);

        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);

    }

    public function setGoodsValues($contractor_id, $contractor_type)
    {
        if (!$this->view->isGoods)
            return false;

//        $this->view->goods_column_names = $this->model->getColumns($this->model->contractor_goods_columns_names,
//            $this->page, $this->model->tableNames[1], true);
//        $roles = new Roles();
//        $this->view->goods_original_columns = $roles->returnModelNames($this->model->contractor_goods_columns_names, $this->page);
        $columnsNames = $this->model->getColumns($this->model->contractor_goods_columns_names,
            $this->page, $this->model->tableNames[1], true);
        $columns = $this->model->getColumns($this->model->contractor_goods_columns,
            $this->page, $this->model->tableNames[1]);
        $selects = $this->model->getSelects($contractor_id, $contractor_type, 'goods');
        $this->view->tableGoods = array_merge($columns, $columnsNames, $selects,
            [
                'table_name' => $this->model->tableNames[1]
            ]
        );

//        $array = $this->model->getSelects($contractor_id, $contractor_type, 'goods');
//        $this->view->goods_selects = $array['selects'];
//        $this->view->goods_rows = $array['rows'];
    }

    public function setServicesValues($contractor_id, $contractor_type)
    {
        if (!$this->view->isServices)
            return false;

//        $array = $this->model->getSelects($contractor_id, $contractor_type, 'services');

//        $this->view->services_column_names = $this->model->getColumns($this->model->contractor_services_columns_names,
//            $this->page, $this->model->tableNames[2], true);
//        $roles = new Roles();
//        $this->view->services_original_columns = $roles->returnModelNames($this->model->contractor_services_columns_names,
//            $this->page);

        $columnsNames = $this->model->getColumns($this->model->contractor_services_columns_names,
            $this->page, $this->model->tableNames[2], true);
        $columns = $this->model->getColumns($this->model->contractor_services_columns,
            $this->page, $this->model->tableNames[2]);
        $selects = $this->model->getSelects($contractor_id, $contractor_type, 'services');
        $this->view->tableServices = array_merge($columns, $columnsNames, $selects,
            [
                'table_name' => $this->model->tableNames[2]
            ]
        );

//        $this->view->services_selects = $array['selects'];
//        $this->view->services_rows = $array['rows'];
    }

    function action_dt_contractor_goods()
    {
	    $print = $this->model->getPrintOptions($_POST);

        $this->model->getContractorGoodsMovement($_POST, $print);
    }

    function action_dt_contractor_services()
    {
	    $print = $this->model->getPrintOptions($_POST);

        echo $this->model->getContractorServices($_POST, $print);
    }

    function action_new_service()
    {

        if (isset($_POST['contractor_id'])) {
            $this->model->addServicesItem($_POST);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }


}