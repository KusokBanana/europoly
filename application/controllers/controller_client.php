<?php

class ControllerClient extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelClient();
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if ($id = $_GET['id']) {
            $this->getAccess('client', 'v');
            if ($id == 'new') {
                $type = isset($_GET['type']) ? $_GET['type'] : false;
                $category = isset($_GET['category']) ? $_GET['category'] : false;
                if ($type == 'fromClient' && isset($_SESSION['new_client']) && !$category) {
                    $this->view->client = $_SESSION['new_client'];
                    unset($_SESSION['new_client']);
                    $this->view->countryAndRegion = $this->model->getCountryAndRegionNamesByIds(
                        $this->view->client['country_id'],
                        $this->view->client['region_id']
                    );
                } elseif ($category) {
                    $this->view->client['type'] = $category;
                }
                $this->view->title = 'New Client';
            } else {
                $this->view->client = $this->model->getClient($id);
                if (!$this->view->client)
                    $this->notFound();

                $this->view->title = 'Client ' . $this->view->client['final_name'];
                $client = $this->view->client;
                $this->view->primaryForm = $this->model->buildPrimaryForm($id);
                $this->view->countryAndRegion = $this->model->getCountryAndRegionNamesByIds($this->view->client['country_id'],
                    $this->view->client['region_id']);
            }
            if (ROLE_SALES_MANAGER == $_SESSION['user_role']) {
                $userId = $_SESSION['user_id'];
                if ((isset($client) && $client['sales_manager_id'] != $userId
                    && $client['operational_manager_id'] != $userId)) {
                    $this->getAccess('none', 'v');
                }
            }
            $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
            $this->view->managers = $this->model->getSalesManagersIdName();
            $this->view->clients = $this->model->getClientsIdName();
            $this->view->build('templates/template.php', 'single_client.php');

        }

    }

    function action_build_form()
    {
        $clientId = isset($_GET['client_id']) ? $_GET['client_id'] : false;
        $type = isset($_GET['type']) ? $_GET['type'] : false;
        if (!$clientId || !$type)
            return false;

        $form = $this->model->buildForm($type);

        echo json_encode($form);
        return 1;
    }

    function action_update_client()
    {
        $clientId = isset($_GET['client_id']) ? $_GET['client_id'] : false;
        if (!$clientId)
            return;

        $clientAdditions = isset($_POST['client_additions']) ? $_POST['client_additions'] : false;
        if ($clientAdditions && !empty($clientAdditions)) {
            $this->model->updateClientAdditions($clientAdditions, $clientId);
            unset($_POST['client_additions']);
        }
        $this->model->updateClient($_POST, $clientId);
        if ($this->user->role_id == ROLE_SALES_MANAGER) {
            $client = $this->model->getFirst("SELECT * FROM clients WHERE client_id = $clientId");
            if (isset($_POST['sales_manager_id']) && $_POST['sales_manager_id'] !== $this->user->user_id
                && $client['operational_manager_id'] !== $this->user->user_id) {
                header("Location: /clients");
            }
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    function action_create_client()
    {

        $clientId = $this->model->createClient($_POST);
        if ($clientId) {
            header("Location: " . '/client?id='.$clientId);
        }
        else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    function action_delete_form_row()
    {
        $pk = isset($_GET['pk']) ? $_GET['pk'] : false;
        if (!$pk)
            return false;
        $this->model->deleteClientAddition($pk);
    }

    function action_check_fields()
    {
        $clientId = isset($_POST['client_id']) ? $_POST['client_id'] : false;
        if (!$clientId)
            return false;

        $client = isset($_POST['client']) ? json_decode($_POST['client']) : false;
        $addition = isset($_POST['additions']) ? json_decode($_POST['additions']) : false;
        $result = $this->model->checkFields($client, $addition, $clientId);
        echo $result;
    }

    function action_import_clients()
    {

        require dirname(__FILE__) . "/../../dump_db/parsers/clients.php";

        $parser = getXLS('clients_v3.xlsx'); //извлеаем данные из XLS

        $this->model->importClients($parser, true);

    }

    function action_copy_client()
    {

        $client_id = isset($_GET['id']) ? $_GET['id'] : false;
        if (!$client_id)
            $this->notFound();

        $post = $this->model->getCopyFromClient($client_id);
        $_SESSION['new_client'] = $post;
        header('Location: /client/index?id=new&type=fromClient');


    }

}