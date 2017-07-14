<?php

class ControllerSent_to_logist extends Controller
{
    public $page = 'sentToLogist';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSent_to_logist();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }


    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $this->view->title = "Requests to Logist";
        $roles = new Roles();
        $this->view->access = $roles->returnAccessAbilities($this->page, 'ch');

        $this->view->itemsTable = $this->model->getTableData();

        $this->view->build('templates/template.php', 'sent_to_logist.php');
    }

    function action_dt_managers_orders()
    {
        $print = isset($_GET['print']) ? $_GET['print'] : false;
        if ($print) {
            $print = [
                'visible' => isset($_GET['visible']) && $_GET['visible'] ? json_decode($_GET['visible'], true) : [],
                'selected' => isset($_GET['selected']) && $_GET['selected'] ? json_decode($_GET['selected'], true) : [],
                'filters' => isset($_GET['filters']) && $_GET['filters'] ? json_decode($_GET['filters'], true) : [],
            ];
        }
        $ids = isset($_GET['ids']) ? $_GET['ids'] : false;
        $this->model->getDTManagersOrders($_GET, $print, $ids);
    }

    function action_to_supplier()
    {
        $ids = isset($_GET['ids']) ? $_GET['ids'] : false;
        $this->model->getDTManagersOrders($_GET, false, $ids);

    }

}
