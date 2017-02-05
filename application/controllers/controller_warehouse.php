<?php

class ControllerWarehouse extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelWarehouse();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->view->full_product_column_names = $this->model->full_product_column_names;
        $this->view->full_product_hidden_columns = $this->model->full_product_hidden_columns;

        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            $this->view->prices = $this->model->getPrices($_GET["id"]);
            if ($id == 0) {
                $this->view->title = "All";
                $this->view->id = 0;
                $this->view->build('templates/template.php', 'warehouse.php');
            } else {
                $this->view->warehouse = $this->model->getById("warehouses", "warehouse_id", $id);
                if ($this->view->warehouse != NULL) {
                    $this->view->id = $this->view->warehouse["warehouse_id"];
                    $this->view->title = $this->view->warehouse["name"];
                    $this->view->build('templates/template.php', 'warehouse.php');
                } else {
                    http_response_code(400);
                }
            }
        } else {
            http_response_code(400);
        }
    }

    function action_dt()
    {
        if (isset($_GET['warehouse_id'])) {
            $warehouse_id = intval($_GET['warehouse_id']);
            if ($warehouse_id === 0) {
                $this->model->getDTProductsForAllWarehouses($_GET);
            } else {
                $this->model->getDTProductsForWarehouse($warehouse_id, $_GET);
            }
        } else {
            http_response_code(400);
        }
    }

    function action_add_product()
    {
        $this->model->addProductsWarehouse(
            $this->escape_and_empty_to_null($_POST['product_ids']),
            $this->escape_and_empty_to_null($_POST['warehouse_id']),
            $this->escape_and_empty_to_null($_POST['amount']),
            $this->escape_and_empty_to_null($_POST['buy_price']));
        header("Location: /warehouse?id=" . $_POST['warehouse_id']);
    }

    function action_transfer()
    {
        $this->model->transferProductWarehouse(
            $this->escape_and_empty_to_null($_POST['product_warehouse_id']),
            $this->escape_and_empty_to_null($_POST['warehouse_id']),
            $this->escape_and_empty_to_null($_POST['amount']));
        header("Location: /warehouse?id=" . $_POST['warehouse_id']);
    }
}