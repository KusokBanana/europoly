<?php
class ControllerLogin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelLogin();
        parent::afterConstruct();
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
            $userName = trim($_POST["username"]);
            $pass = trim($_POST["password"]);
            $user_exist = $this->model->getUserByEmailAndPassword($userName, md5($pass));
            if ($user_exist) {
                $_SESSION["user_connected"] = true;
                $roleId = $user_exist['role_id'];
                $_SESSION["user_id"] = $user_exist['user_id'];
                $_SESSION["user_role"] = $user_exist['role_id'];
                $_SESSION["perm"] = $this->model->getRolePermissions($roleId);
                $_SESSION["avatar"] = $user_exist['avatar_url'];
            }
            header("Location: /");
        }
    }

    function action_logout()
    {
        session_destroy();
        header("Location: /");
    }

    function action_hidden_sidebar()
    {
        if (isset($_GET["visible"])) {
            $isVisible = $_GET["visible"];
            $_SESSION[SESSION_SIDEBAR] = $isVisible;
        }
    }

    function action_save_order_columns()
    {
        if (isset($_POST["columns"])) {
            $columns = $_POST["columns"];
            $tableId = $_POST['tableId'];
            if (in_array($tableId, ["table_warehouses_products","table_warehouses_products_issue","table_warehouses_products_reserved"]))
                $tableId = 'table_warehouse';
            if (in_array($tableId, ["new_supplier_order_table", "sent_to_logist"]))
                $tableId = 'sent_to_logist';
            if (in_array($tableId, ["table_end_customers","table_commission_agents","table_dealers"]))
                $tableId = 'table_clients';
            if (in_array($tableId, ['modal_catalogue1', 'modal_catalogue11']))
                $tableId = 'modal_catalogue';
            echo $this->model->saveOrderColumns($columns, $tableId);
        }
    }
    function action_save_sort_columns()
    {
        if (isset($_POST["sort"])) {
            $sort = $_POST["sort"];
            $tableId = $_POST['tableId'];

            $currentSort = isset($_SESSION['sort_columns']) ? $_SESSION['sort_columns'] : [];
            $currentSort[$tableId] = $sort;
//            setcookie('sort_columns', $currentSort);
            $_SESSION['sort_columns'] = $currentSort;
        }
    }

    function action_save_records_total()
    {
        if (isset($_POST["count"])) {
            $count = $_POST["count"];
            $tableId = $_POST['tableId'];
            $this->model->saveRecordsCount($count, $tableId);
        }
    }

    function action_save_resize_columns()
    {
        if (isset($_POST["column_id"])) {
            $column_width = array(
                'id'=>$_POST["column_id"],
                'value'=>$_POST["column_width"]
            );
            $tableId = $_POST['tableId'];
            $this->model->saveResizeColumns($column_width, $tableId);
        }
    }

    function action_width_columns()
    {
        echo $this->model->getResizeColumns();
        echo $_POST["tableId"];
    }
}