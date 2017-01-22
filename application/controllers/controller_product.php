<?php

class ControllerProduct extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelProduct();
    }

    function action_index($action_param = null, $action_data = null)
    {
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            $this->view->product = $this->model->getById("products", "product_id", $id);
            if ($this->view->product != NULL) {
                $this->view->full_product = $this->model->getFullProductEntity($id);
                $this->view->rus = $this->model->getRus($id);
                $this->view->brand = $this->model->getById("brands", "brand_id", $this->view->product["brand_id"]);
                $this->view->title = $this->view->product["name"];
                $this->view->photos = $this->model->getPhotos($this->view->product["product_id"]);

                $this->view->brands = $this->model->getAll("brands");
                $this->view->colors = $this->model->getAll("colors");
                $this->view->constructions = $this->model->getAll("constructions");
                $this->view->wood = $this->model->getAll("wood");
                $this->view->grading = $this->model->getAll("grading");
                $this->view->patterns = $this->model->getAll("patterns");

                $this->view->balances = $this->model->getBalances($id);
                $this->view->all_warehouces_balance = $this->model->getAllWarehousesBalance($id);

                $this->view->build('templates/template.php', 'single_product.php');
            } else {
                http_response_code(400);
            }
        } else {
            http_response_code(400);
        }
    }

    function action_delete()
    {
        if (isset($_POST["product_id"])) {
            $product_id = intval($_POST["product_id"]);
            $this->model->deleteProduct($product_id);
        }
    }

    function action_upload_image()
    {
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
        if (isset($_POST["photo_id"])) {
            $photo_id = intval($_POST["photo_id"]);
            $this->model->deletePhoto($photo_id);
        }
    }

    function action_change_field()
    {
        if (isset($_POST["pk"]) && isset($_POST["name"]) && isset($_POST["value"])) {
            $product_id = intval($_POST["pk"]);
            $name = $this->model->escape_string($_POST["name"]);
            $value = $this->model->escape_string($_POST["value"]);
            if (!$this->model->updateField($product_id, $name, $value)) {
                http_response_code(500);
            } else {
                echo $value;
            }
        } else {
            http_response_code(400);
        }
    }

    function action_change_status()
    {
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