<?php

class ControllerSales_manager extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelSales_manager();
        parent::afterConstruct();
    }

    public $page = 'sales manager';

    function action_index($action_param = null, $action_data = null)
    {
        $roles = new Roles();

        if ($roles->returnAccessAbilities($this->page, 'v')) {
            $userId = $_SESSION['user_id'];
            if ($userId !== $_GET['id']) {
                $this->getAccess('none', 'v');
            }
            $this->getAccess('sales manager', 'v');
            $this->view->access = $roles->getPageAccessAbilities($this->page);

            $this->view->manager = $this->model->getUser($_GET['id']);

            if ($this->view->manager['is_deleted']) {
                http_response_code(400);
                die();
            }

            $this->view->title = $this->view->manager['first_name'] . " " . $this->view->manager['last_name'];
            $this->view->managers = $this->model->getSalesManagersIdName();
            $this->view->clients = $this->model->getClientsOfSalesManager($this->view->manager['user_id']);
            $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
            $this->view->roles = $this->model->getRoles();
            $this->view->build('templates/template.php', 'single_manager.php');
        } else {
            http_response_code(400);
        }

    }

    function action_update_personal_info()
    {
        $this->model->updatePersonalInfo($this->escape_and_empty_to_null($_POST['user_id']),
            $this->escape_and_empty_to_null($_POST['first_name']),
            $this->escape_and_empty_to_null($_POST['last_name']),
            $this->escape_and_empty_to_null($_POST['date_of_birth']),
            $this->escape_and_empty_to_null($_POST['position']),
            $this->escape_and_empty_to_null($_POST['work_phone']),
            $this->escape_and_empty_to_null($_POST['mobile_number']),
            $this->escape_and_empty_to_null($_POST['email']),
            $this->escape_and_empty_to_null($_POST['employment_date']),
            $this->escape_and_empty_to_null($_POST['notes']),
            isset($_POST['role_id']) ?
                $this->escape_and_empty_to_null($_POST['role_id']) : false);
        header("Location: /sales_manager?id=" . $_POST['user_id'] . "#tab_1-1");
    }

    function action_update_salary_settings()
    {
        $this->getAccess('sales manager', 'ch');
        $this->model->updateSalarySettings($this->escape_and_empty_to_null($_POST['user_id']),
            $this->escape_and_empty_to_null($_POST['salary']),
            $this->escape_and_empty_to_null($_POST['manager_bonus_rate']));
        header("Location: /sales_manager?id=" . $_POST['user_id'] . "#tab_4-4");
    }

    function action_update_avatar()
    {
        $user_id = $_POST["user_id"];
        $action = $_POST["action"];
        if ($action == "update") {
            $target_dir = __DIR__ . "/../../avatars/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $extension = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
            $name = hash('md5', basename($_FILES["fileToUpload"]["name"]) . time()) . "." . $extension;
            $target_file = $target_dir . $name;

            if ($this->upload_photo($target_file)) {
                $this->model->updateAvatar($user_id, $name);
            }
        } else if ($action == 'delete') {
            $this->model->updateAvatar($user_id, null);
        }
        header("Location: /sales_manager?id=" . $user_id . "#tab_2-2");
    }

    function action_update_account()
    {
        $this->getAccess('sales manager', 'ch');
        $this->model->updateAccount($this->escape_and_empty_to_null($_POST['user_id']),
            $this->escape_and_empty_to_null($_POST['login']),
            md5($this->escape_and_empty_to_null($_POST['password'])));
        header("Location: /sales_manager?id=" . $_POST['user_id'] . "#tab_3-3");
    }

    function action_dt_clients()
    {
        $this->model->getDTClients($_GET['user_id'], $_GET);
    }

    function action_dt_orders()
    {
        $this->model->getDTOrders($_GET['user_id'], $_GET);
    }

    function action_add_order()
    {
        $order_id = $this->model->addOrder($this->escape_and_empty_to_null($_POST['sales_manager_id']),
            $this->escape_and_empty_to_null($_POST['client_id']));
        header("Location: /order?id=" . $order_id);
    }

    function action_delete_user()
    {
        if (isset($_GET['id']) && $_GET['id']) {
            $this->getAccess($this->page, 'v');
            $user_id = $_GET['id'];
            $this->model->deleteUser($user_id);
            header("Location: /staff");
        }
    }
}