<?php

class ControllerChat extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelChat();
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {}

    function action_check_messages()
    {
        if (isset($_POST['user_id']) && isset($_POST['type'])) {

            $user_id = $_POST['user_id'];
            $type = $_POST['type'];
            $companion = isset($_POST['companion_id']) ? $_POST['companion_id'] : 0;

            echo $this->model->checkMessages($user_id, $type, $companion);

        }
    }
    function action_send_message()
    {
        if (isset($_POST['user_id'])) {

            $user_id = $_POST['user_id'];
            $companion_id = $_POST['companion_id'];
            $message = $_POST['message'];

            echo $this->model->sendMessage($user_id, $companion_id, $message);

        }
    }

    function action_read_messages()
    {
        if (isset($_POST['user_id'])) {

            $user_id = $_POST['user_id'];
            $companion_id = $_POST['companion_id'];

            echo $this->model->readMessages($user_id, $companion_id);

        }
    }

}