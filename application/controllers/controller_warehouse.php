<?php

class ControllerWarehouse extends Controller
{

    public $page = 'warehouse';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelWarehouse();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $roles = new Roles();
        $this->view->access = $roles->returnAccessAbilities($this->page, 'ch');
        $this->view->warehouses = $this->model->getWarehousesIdNames();

        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            $this->view->prices = $this->model->getPrices($_GET["id"]);
            $array = $this->model->getSelects($id);
            $selects = $array['selects'];
            $rows = $array['rows'];
            $this->view->selects = $selects;
            $this->view->rows = $rows;

            $this->view->column_names = $this->model->getColumns($this->model->product_warehouses_column_names,
                $this->page, $this->model->tableName, true);
            $this->view->originalColumns = $roles->returnModelNames($this->model->product_warehouses_column_names, $this->page);

            $this->view->products_column_names = $this->model->getColumns($this->model->full_product_column_names,
                $this->page, 'table_catalogue', true);
            $this->view->products_hidden_columns = $this->model->full_product_hidden_columns;
            $this->view->products_originalColumns = $roles->returnModelNames($this->model->full_product_column_names, $this->page);

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
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            $this->model->getDTProductsForWarehouses($_GET, $warehouse_id, $type);
        } else {
            http_response_code(400);
        }
    }

    function action_add_product()
    {
        $this->getAccess($this->page, 'ch');
        $docName = $this->model->addProductsWarehouse(
            $this->escape_and_empty_to_null($_POST['product_ids']),
            $this->escape_and_empty_to_null($_POST['warehouse_id']),
            $this->escape_and_empty_to_null($_POST['amount']),
            $this->escape_and_empty_to_null($_POST['buy_price']));
        $url = "Location: /warehouse?id=" . $_POST['warehouse_id'];
        $roles = new Roles();
        if ($roles->getPageAccessAbilities('warehouse')['p']) {
            $url .= '&documentPath=' . $docName;
        }
        header($url);
    }

    function action_change_item_field()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_POST["pk"]) && isset($_POST["name"]) && isset($_POST["value"])) {
            $order_item_id = intval($_POST["pk"]);
            $name = $this->model->escape_string($_POST["name"]);
            $value = $this->model->escape_string($_POST["value"]);
            if (!$this->model->updateItemField($order_item_id, $name, $value)) {
                http_response_code(500);
            } else {
                echo $value;
            }
        } else {
            http_response_code(400);
        }
    }
}