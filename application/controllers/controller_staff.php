<?php

class ControllerStaff extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelStaff();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Staff";
        $this->view->build('templates/template.php', 'staff.php');
    }

    function action_dt_managers()
    {
        $this->model->getDTManagers($_GET);
    }

    function action_dt_support()
    {
        $this->model->getDTSupport($_GET);
    }

    function action_add()
    {
        $user_id = $this->model->addUser($this->escape_and_empty_to_null($_POST['first_name']),
            $this->escape_and_empty_to_null($_POST['last_name']),
            $this->escape_and_empty_to_null($_POST['role']),
            $this->escape_and_empty_to_null($_POST['login']),
            $this->escape_and_empty_to_null($_POST['password']));
        if ($user_id != false && $_POST['role'] == 'Sales Manager') {
            header("Location: /sales_manager?id=" . $user_id);
        } else if ($user_id != false && $_POST['role'] == 'Support') {
            header("Location: /support?id=" . $user_id);
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}