<?php

class ControllerLogin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelLogin();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->build('login.php');
    }

    function action_login()
    {
        session_destroy();
        session_start();

        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $user_exist = $this->model->getUserByEmailAndPassword($_POST["username"], md5($_POST["password"]));
            if ($user_exist) {
                $_SESSION["user_connected"] = true;
                $_SESSION["user_id"] = $user_exist['user_id'];
                $_SESSION["user_role"] = $user_exist['role'];
            }
            header("Location: /");
        }
    }

    function action_logout()
    {
        session_destroy();
        header("Location: /");
    }
}