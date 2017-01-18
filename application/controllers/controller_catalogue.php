<?php

class ControllerCatalogue extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelCatalogue();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->title = "Catalogue";
        $this->view->full_product_column_names = $this->model->full_product_column_names;
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;

        $this->view->brands = $this->model->getAll("brands");
        $this->view->wood = $this->model->getAll("wood");
        $this->view->colors = $this->model->getAll("colors");
        $this->view->constructions = $this->model->getAll("constructions");
        $this->view->grading = $this->model->getAll("grading");
        $this->view->patterns = $this->model->getAll("patterns");
        $this->view->tabs = $this->model->getCategoryTabs();
        $this->view->selects = $this->model->getSelects();

        $this->view->build('templates/template.php', 'catalogue.php');
    }

    function action_dt()
    {
        $id = isset($_GET['products']['id']) ? $_GET['products']['id'] : false;
        $this->model->getDTProducts($_GET, $id);
    }

    function action_dt_ajax_filter()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : false;
        echo $this->model->getAjaxColumn($id);
    }

    function action_add()
    {
        $postArray = [];
        $rusArray = [];
        foreach ($_POST as $key => $post) {
            if ($key == 'RUS' && !empty($post)) {
                foreach ($post as $rusKey=>$rusValue) {
                    $rusArray[$rusKey] = $this->escape_and_empty_to_null($rusValue);
                }
                unset($_POST['RUS']);
            }
            $postArray[$key] = $this->escape_and_empty_to_null($post);
        }
        $this->model->addProduct($postArray, $rusArray);
        header("Location: /catalogue");
    }
}