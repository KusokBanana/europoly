<?php

class ControllerClient extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelClient();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if ($id = $_GET['id']) {
            if ($id == 'new') {
                $this->view->title = 'New Client';
                $this->view->commission_agents = $this->model->getCommissionAgentsIdName();
            } else {
                $this->view->client = $this->model->getClient($id);
                $this->view->title = $this->view->client['name'];
                $this->view->primaryForm = $this->model->buildPrimaryForm($id);
                $this->view->countryAndRegion = $this->model->getCountryAndRegionNamesByIds($this->view->client['country_id'],
                    $this->view->client['region_id']);
            }
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

        $parser = getXLS('clients.xlsx'); //извлеаем данные из XLS

        $this->model->importClients($parser);

    }

}