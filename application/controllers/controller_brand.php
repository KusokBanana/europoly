<?php

class ControllerBrand extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelBrand();
        $this->model->page = $this->page;
        parent::afterConstruct();
    }

    public $page = 'brand';

    function action_index($action_param = null, $action_data = null)
    {
        if (isset($_GET["id"])) {
            $this->getAccess($this->page, 'v');
            $id = intval($_GET["id"]);
            $this->view->brand = $this->model->getById("brands", "brand_id", $id);
            if ($this->view->brand != NULL) {
                $this->view->title = $this->view->brand["name"];
                $roles = new Roles();

                $this->view->access = $roles->getPageAccessAbilities($this->page);

                $this->view->brandsTable = $this->model->getTableData('general', ['brand_id' => $id]);

                $this->view->brands = $this->model->getAll("brands");
                $this->view->colors = $this->model->getAll("colors");
                $this->view->constructions = $this->model->getAll("constructions");
                $this->view->wood = $this->model->getAll("wood");
                $this->view->grading = $this->model->getAll("grading");
                $this->view->patterns = $this->model->getAll("patterns");
                $this->view->tabs = $this->model->getCategoryTabs();
                $this->view->categories = $this->model->getAll('category');

                // try to read cache
                $cache = new Cache();
                $this->view->new_product_selects = $cache->getOrSet('new_product_selects', function() {
                    return $this->model->newProductSelects();
                });

                $this->view->build('templates/template.php', 'single_brand.php');
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    function action_dt()
    {
        if (isset($_POST["products"])) {
            $data = $_POST["products"];
            $brand_id = intval($data['brand_id']);

            $print = isset($_POST['print']) ? $_POST['print'] : false;
            if ($print) {
                $print = [
                    'visible' => isset($_POST['visible']) && $_POST['visible'] ? json_decode($_POST['visible'], true) : [],
                    'selected' => isset($_POST['selected']) && $_POST['selected'] ? json_decode($_POST['selected'], true) : [],
                    'filters' => isset($_POST['filters']) && $_POST['filters'] ? json_decode($_POST['filters'], true) : [],
                ];
            }

            $this->model->getDTProductsForBrand($brand_id, $_POST, $print);
        } else {
            http_response_code(400);
        }
    }
}