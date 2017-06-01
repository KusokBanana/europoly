<?php

class ControllerProduct extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelProduct();
        parent::afterConstruct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if (isset($_GET["id"])) {
            $this->getAccess('product', 'v');
            $id = intval($_GET["id"]);
            $this->view->product = $this->model->getById("products", "product_id", $id);
            if ($this->view->product != NULL && $this->view->product['is_deleted'] == 0) {
                $this->view->rus = $this->model->getRus($id);
                $this->view->brand = $this->model->getById("brands", "brand_id", $this->view->product["brand_id"]);
                $this->view->title = $this->view->product["name"];
                $this->view->photos = $this->model->getPhotos($this->view->product["product_id"]);

                $roles = new Roles();
                $this->view->access = $roles->getPageAccessAbilities('product');
//                $this->view->balances = $this->model->getBalances($id);
//                $this->view->all_warehouces_balance = $this->model->getAllWarehousesBalance($id);

                // try to read cache
                $cache = new Cache();
                $selectsCache = $cache->read('product_selects');
                if (!empty($selectsCache)) {
                    $selects = $selectsCache;
                } else {
                    $selects = $this->model->getSelects();
                    $cache->write('product_selects', $selects);
                }
                $this->view->selects = $selects;
                $this->view->columns = $roles->columnsNamesAccess($this->model->columns, 'product');

                $this->view->build('templates/template.php', 'single_product.php');
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    function action_delete()
    {
        $this->getAccess('product', 'd');
        if (isset($_GET["product_id"])) {
            $product_id = intval($_GET["product_id"]);
            $this->model->deleteProduct($product_id);
            header("Location: /catalogue");
        }
    }

    function action_upload_image()
    {
        $this->getAccess('product', 'ch');
        $target_dir = __DIR__ . "/../../images/" . $this->model->getById("products", "product_id", $_POST["product_id"])['brand'] . '/';
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $name = hash('md5', basename($_FILES["fileToUpload"]["name"]) . time());
        $target_file = $target_dir . $name . '.jpg';

        $product_id = $_POST["product_id"];
        if ($this->upload_photo($target_file)) {
            $this->model->addPhoto($product_id, $name);
            header("Location: /product?id=$product_id");
        }
    }

    function action_delete_photo()
    {
        $this->getAccess('product', 'd');
        if (isset($_POST["photo_id"])) {
            $photo_id = intval($_POST["photo_id"]);
            $this->model->deletePhoto($photo_id);
        }
    }

    function action_change_field()
    {
        $this->getAccess('product', 'ch');
        if (isset($_POST["pk"]) && isset($_POST["name"]) && isset($_POST["value"])) {
            $product_id = intval($_POST["pk"]);
            $name = $this->model->escape_string($_POST["name"]);
            $value = $this->model->escape_string($_POST["value"]);
            if (!$this->model->updateField($product_id, $name, $value)) {
                http_response_code(500);
            } else {
                echo $value;
                header("Location: /product?id=$product_id");
            }
        } else {
            http_response_code(400);
        }
    }

    function action_change_status()
    {
        $this->getAccess('product', 'ch');
        if (isset($_POST["product_id"]) && isset($_POST["new_status"])) {
            $product_id = intval($_POST["product_id"]);
            $new_status = intval($_POST["new_status"]);
            if ($this->model->updateField($product_id, "status", $new_status)) {
                echo $new_status;
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(400);
        }
    }
}