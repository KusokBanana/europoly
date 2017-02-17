<?php

class ControllerClients extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelClients();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess('clients', 'v');
        $this->view->title = "Clients";
        $roles = new Roles();
        $this->view->access = $roles->returnAccessAbilities('clients', 'ch');

        $this->view->column_names = $this->model->client_column_names;
        $this->view->managers = $this->model->getSalesManagersIdName();
        $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
        $this->view->build('templates/template.php', 'clients.php');
    }

    function action_dt_clients()
    {
        $this->model->getDTClients($_GET);
    }

    function action_dt_end_customers()
    {
        $this->model->getDTEndCustomers($_GET);
    }

    function action_dt_commission_agents()
    {
        $this->model->getDTCommissionAgents($_GET);
    }

    function action_dt_dealers()
    {
        $this->model->getDTDealers($_GET);
    }

    function action_get_countries()
    {
        $query = isset($_GET['q']) ? $_GET['q'] : "";
        $countries = $this->model->getCountriesIdName($query);
        echo json_encode(["items" => $countries, "total_count" => count($countries)]);
    }

    function action_get_regions()
    {
        $query = isset($_GET['q']) ? $_GET['q'] : "";
        $country_id = $_GET['country_id'];
        $regions = $this->model->getRegionsIdName($query, $country_id);
        echo json_encode(["items" => $regions, "total_count" => count($regions)]);
    }

    function action_add()
    {
        $this->getAccess('clients', 'ch');
        $this->model->addClient(
            $this->escape_and_empty_to_null($_POST['name']),
            $this->escape_and_empty_to_null($_POST['type']),
            $this->escape_and_empty_to_null($_POST['sales_manager_id']),
            $this->escape_and_empty_to_null($_POST['commission_agent_id']),
            $this->escape_and_empty_to_null($_POST['country_id']),
            $this->escape_and_empty_to_null($_POST['region_id']),
            $this->escape_and_empty_to_null($_POST['city']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_change_item_field()
    {
        $this->getAccess('clients', 'ch');
        if (isset($_POST["pk"]) && isset($_POST["name"]) && isset($_POST["value"])) {
            $client_id = intval($_POST["pk"]);
            $name = $this->model->escape_string($_POST["name"]);
            $value = $this->model->escape_string($_POST["value"]);
            if (!$this->model->updateItemField($client_id, $name, $value)) {
                http_response_code(500);
            } else {
                echo $value;
            }
        } else {
            http_response_code(400);
        }
    }

    function action_hidden_columns()
    {
        if (isset($_POST["columnsHidden"])) {
            $columns = json_decode($_POST["columnsHidden"], true);
            $tableId = $columns['tableId'];
            if (!$tableId)
                return false;
            $action = $columns['action'];
            if ($action == 'change') {
                $columnsId = $columns['ids'];
                $userCookies = isset($_COOKIE['hiddenColumns']) ? $_COOKIE['hiddenColumns'] : false;
                if ($userCookies) {
                    $cookies = json_decode($userCookies,true);
                    $cookies[$tableId] = $columnsId;
                    $newCookie = $cookies;
                }
                else {
                    $hiddenColumns = [
                        $tableId => [$columnsId]
                    ];
                    $newCookie = $hiddenColumns;
                }
                $jsonHiddenColumns = json_encode($newCookie);
                setcookie('hiddenColumns', $jsonHiddenColumns);
                return true;
            }
            if ($action == 'get') {
                $userCookies = isset($_COOKIE['hiddenColumns']) ? $_COOKIE['hiddenColumns'] : false;
                if ($userCookies) {
                    $cookies = json_decode($userCookies,true);
                    if (!empty($cookies) && isset($cookies[$tableId])) {
                        echo json_encode($cookies[$tableId]);
                        return true;
                    } else {
                        echo false;
                        return false;
                    }
                }
            }
        }
    }

}