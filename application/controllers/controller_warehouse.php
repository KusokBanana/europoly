<?php

class ControllerWarehouse extends Controller
{

    public $page = 'warehouse';

    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelWarehouse();
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        $this->getAccess($this->page, 'v');
        $roles = new Roles();
        $this->view->access = $roles->getPageAccessAbilities($this->page);
        $this->view->warehouses = $this->model->getWarehousesIdNames();
        $this->view->logs = $this->model->getLogs();

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

            $this->view->access = $roles->getPageAccessAbilities('warehouse');
            if ($this->view->access['p']) {
                $this->view->documents = $this->model->getDocuments($_GET['id']);
            }

            $cache = new Cache();
            $selectsCache = $cache->read('catalogue_selects');
            if (!empty($selectsCache)) {
                $array = $selectsCache;
                $selects = $array['selects'];
                $rows = $array['rows'];
            } else {
                $array = $this->model->getSelects();
                $selects = $array['selects'];
                $rows = $array['rows'];
                $cache->write('catalogue_selects', $array);
            }
            $this->view->catalogue_selects = $selects;
            $this->view->catalogue_rows = $rows;


            if ($id == 0) {
                $this->view->title = "All";
                $this->view->id = 0;
            } else {
                $this->view->warehouse = $this->model->getById("warehouses", "warehouse_id", $id);
                if ($this->view->warehouse != NULL) {
                    $this->view->id = $this->view->warehouse["warehouse_id"];
                    $this->view->title = $this->view->warehouse["name"];
                } else {
                    http_response_code(400);
                }
            }
            $this->view->build('templates/template.php', 'warehouse.php');
        } else {
            http_response_code(400);
        }
    }

    function action_dt()
    {
        if (isset($_GET['warehouse_id'])) {
            $print = isset($_POST['print']) ? $_POST['print'] : false;
            if ($print) {
                $print = [
                    'visible' => isset($_POST['visible']) && $_POST['visible'] ? json_decode($_POST['visible'], true) : [],
                    'selected' => isset($_POST['selected']) && $_POST['selected'] ? json_decode($_POST['selected'], true) : [],
                    'filters' => isset($_POST['filters']) && $_POST['filters'] ? json_decode($_POST['filters'], true) : [],
                ];
            }

            $warehouse_id = intval($_GET['warehouse_id']);
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            $this->model->getDTProductsForWarehouses($_GET, $warehouse_id, $type, $print);
        } else {
            http_response_code(400);
        }
    }

    function action_add_product()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_POST['NewWarehouseProduct']) && isset($_POST['warehouse_id'])) {
            $products = $_POST['NewWarehouseProduct'];
            $warehouse_id = $_POST['warehouse_id'];
            $this->model->addProductsWarehouse($products, $warehouse_id);

            $url = "Location: /warehouse?id=" . $warehouse_id;
            header($url);
        }

//        $docName = $this->model->addProductsWarehouse(
//            $this->escape_and_empty_to_null($_POST['product_ids']),
//            $this->escape_and_empty_to_null($_POST['warehouse_id']),
//            $this->escape_and_empty_to_null($_POST['amount']),
//            $this->escape_and_empty_to_null($_POST['buy_price']));
//        $roles = new Roles();
//        if ($roles->getPageAccessAbilities('warehouse')['p']) {
//            $url .= '&documentPath=' . $docName;
//        }
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

    function action_print_doc()
    {
        if (isset($_GET['warehouse_id'])) {
            $warehouse_id = $_GET['warehouse_id'];
            $result = $this->model->printDoc($warehouse_id, $this->model->where_issue, 'expects_issue');
            echo $result;
        }
    }

    function action_issue_products()
    {

        if (isset($_GET['products'])) {
            $products = $_GET['products'];
            $this->model->issueProducts($products);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

    }
    function action_discard_products()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_GET['products'])) {
            $products = $_GET['products'];
            $this->model->discardProducts($products);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    function action_assemble_set_submit()
    {
        $this->getAccess($this->page, 'ch');
        if (isset($_POST['Assemble']) && isset($_POST['Assemble']['warehouse']) && isset($_POST['Assemble']['product'])) {
            $assembleWarehouseProducts = $_POST['Assemble']['warehouse'];
            $assembleProduct = $_POST['Assemble']['product'];
            $warehouseId = $_POST['warehouse_id'];
            $this->model->submitAssemble($assembleWarehouseProducts, $assembleProduct, $warehouseId);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    function action_dt_assemble()
    {

        if (isset($_GET['items'])) {
            $this->model->getDTProductsAssembleSource($_GET, $_GET['items']);
        }

    }

    function action_print_log_doc()
    {
        if (isset($_GET['id'])) {
            echo $this->model->printLogDoc($_GET['id']);
        }
    }
}