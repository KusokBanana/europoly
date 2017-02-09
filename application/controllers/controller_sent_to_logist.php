<?php

class ControllerSent_to_logist extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSent_to_logist();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Sent to logist";
        $this->view->column_names = $this->model->managers_orders_column_names;

        $cache = new Cache();
        $selectsCache = $cache->read('sent_to_logist');
        if (!empty($selectsCache)) {
            $array = $selectsCache;
            $selects = $array['selects'];
            $rows = $array['rows'];
        } else {
            $array = $this->model->getSelects();
            $selects = $array['selects'];
            $rows = $array['rows'];
            $cache->write('sent_to_logist', $array);
        }
        $this->view->selects = $selects;
        $this->view->rows = $rows;

        $this->view->build('templates/template.php', 'sent_to_logist.php');
    }

    function action_dt_managers_orders()
    {
        $this->model->getDTManagersOrders($_GET);
    }
}
