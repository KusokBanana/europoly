<?php

class Roles {

    private $permissions;
    private $columns;
    private $names;
    private $model = '';
    private $accessToChange = false;
    private $page = false;

    public function returnModelColumns($columns, $model)
    {
        $this->permissions = (isset($_SESSION['perm']) && $_SESSION['perm']) ? $_SESSION['perm'] : 0;
        $this->columns = $columns;
        $this->model = $model;
        $this->access();
        return $this->columns;
    }
    public function returnModelNames($names, $model)
    {
        $this->permissions = (isset($_SESSION['perm']) && $_SESSION['perm']) ? $_SESSION['perm'] : 0;
        $this->names = $names;
        $this->model = $model;
        $this->access();
        return $this->names;
    }

    private function access()
    {
        if ($model = $this->model) {
            switch ($perms = $this->permissions) {
                case ($perms >= ADMIN_PERM):
                    break;
                case ($perms >= SALES_MANAGER_PERM):
                    $this->tableAccess();
                    break;
                case ($perms >= ACCOUNTANT_PERM):
                    $this->tableAccess();
                    break;
                case ($perms >= WAREHOUSE_PERM):
                    $this->tableAccess();
                    break;
            }
        }
        return false;
    }

    private $brandModelColumns = [25, 26, 27, 28, 31, 32];
    private $catalogueModelColumns = [25, 26, 27, 28, 31, 32];
    private $suppliersOrdersModelColumns = [15, 16, 17, 18, 23, 24, 29];
    private $shipmentModelColumns = [16, 17, 18, 19, 25, 26, 30];
    private $suppliersOrderModelColumns = [4, 5];
    private $truckModelColumns = [4, 5, 15, 16, 17];
    private $sentToLogistModelColumns = [11, 12, 13, 14, 27];
    private $warehouseModelColumns = [9, 10, 11, 12, 13, 14, 15, 20, 21, 22, 23, 36];
    private $productModelColumns = ['suppliers_discount', 'margin', 'currency', 'purchase_price'];
    private $orderModelColumns = [5, 6];

    private function tableAccess()
    {
        $columns = $this->columns;
        $modelColumnsClosed = $this->model . 'ModelColumns';
        $count = 0;
        if (isset($this->$modelColumnsClosed)) {
            if (!empty($columns)) {
                foreach ($columns as $key => $column) {
                    $dt = $column['dt'];
                    if (in_array($dt, $this->$modelColumnsClosed)) {
                        unset($columns[$key]);
                        $count++;
                        continue;
                    }
                    if ($count) {
                        $columns[$key]['dt'] = $dt - $count;
                        continue;
                    }
                    $count = 0;
                }
                $this->columns = array_values($columns);
            }
        }
        $this->columnsNamesAccess();
        return false;
    }

    /**
     * @param bool|array $returnNames
     * @param string $model
     * @return bool
     */
    public function columnsNamesAccess($returnNames = false, $model = '')
    {
        $names = $returnNames ? $returnNames : $this->names;
        $model = $model ? $model : $this->model;
        $modelColumnsClosed = $model . 'ModelColumns';
        if (isset($this->$modelColumnsClosed)) {
            if (!empty($names)) {
                foreach ($names as $key => $name) {
                    if (in_array($key, $this->$modelColumnsClosed)) {
                        unset($names[$key]);
                    }
                }
                if ($returnNames) {
                    return $names;
                } else {
                    $this->names = array_values($names);
                }
            }
        }
    }

    public function returnAccessAbilities($page, $action)
    {
        $this->permissions = (isset($_SESSION['perm']) && $_SESSION['perm']) ? $_SESSION['perm'] : 0;
        $this->page = $page;
        if ($page) {
            switch ($perms = $this->permissions) {
                case ($perms >= ADMIN_PERM):
                    return true;
                case ($perms >= SALES_MANAGER_PERM):
                    return $this->isCan($page, $action, ROLE_SALES_MANAGER);
                    break;
                case ($perms >= ACCOUNTANT_PERM):
                    return $this->isCan($page, $action, ROLE_ACCOUNTANT);
                    break;
                case ($perms >= WAREHOUSE_PERM):
                    return $this->isCan($page, $action, ROLE_WAREHOUSE);
                    break;
            }
        }
    }

    private function isCan($page, $action, $role_id)
    {
        return (isset($this->permissionsAccess[$role_id]) && isset($this->permissionsAccess[$role_id][$action]) &&
            isset($this->permissionsAccess[$role_id][$action][$page])) ?
            $this->permissionsAccess[$role_id][$action][$page] : false;
    }

    private $permissionsAccess = [
        ROLE_SALES_MANAGER => [
            'ch' => [ // change
                'clients' => true,
                'order' => true,
                'staff' => true,
                'brands' => true,
                'client' => true,
            ],
            'd' => [ // delete
                'order' => true,
            ],
            'v' => [ // visit
                'brands' => true,
                'brand' => true,
                'product' => true,
                'products' => true,
                'catalogue' => true,
                'clients' => true,
                'client' => true,
                'managersOrders' => true,
                'order' => true,
                'sales manager' => true,
                'staff' => true,
                'shipment' => true,
                'suppliersOrders' => true,
                'warehouse' => true,
                'sentToLogist' => true,
            ],
            'p' => [ // print
                'order' => true
            ]
        ],
        ROLE_ACCOUNTANT => [
            'ch' => [
                'payment' => true,
                'accountant' => true,
            ],
            'd' => [
                'accountant' => true,
            ],
            'v' => [
                'catalogue' => true,
                'support' => true,
                'accountant' => true,
                'payment' => true,
            ]
        ],
        ROLE_WAREHOUSE => [
            'ch' => [
                'warehouse' => true,
            ],
            'd' => [
                'warehouse' => true,
            ],
            'v' => [
                'catalogue' => true,
                'support' => true,
                'warehouse' => true,
            ]
        ]
    ];

    private $actions = ['ch', 'd', 'v', 'p'];

    public function getPageAccessAbilities($page)
    {
//        $this->permissions = (isset($_SESSION['perm']) && $_SESSION['perm']) ? $_SESSION['perm'] : 0;
        $role = (isset($_SESSION['user_role']) && $_SESSION['user_role']) ? $_SESSION['user_role'] : 0;
        $this->page = $page;
        $accessAbilities = [];
        if ($page) {
            if ($role == ROLE_ADMIN) {
                foreach ($this->actions as $key => $val) {
                    $accessAbilities[$val] = true;
                }
            } else {
                $abilities = $this->permissionsAccess[$role];
                if (!empty($abilities)) {
                    foreach ($abilities as $action => $arrayPage) {
                        if (isset($arrayPage[$page]) && $arrayPage[$page]) {
                            $accessAbilities[$action] = true;
                        }
                    }
                    if (count($accessAbilities) < count($this->actions)) {
                        foreach ($this->actions as $val) {
                            if (!isset($accessAbilities[$val])) {
                                $accessAbilities[$val] = false;
                            }
                        }
                    }
                }
            }
            return $accessAbilities;
        }
    }

}
