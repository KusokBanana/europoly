<?php

class ModelStaff extends Model
{
    var $manager_columns = array(
        array('dt' => 0, 'db' => "users.user_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"\sales_manager?id=', users.user_id, '\">', users.first_name, ' ', users.last_name, '</a>')"),
        array('dt' => 2, 'db' => "roles.name"),
    );

    var $support_columns = array(
        array('dt' => 0, 'db' => "users.user_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"\support?id=', users.user_id, '\">', users.first_name, ' ', users.last_name, '</a>')"),
        array('dt' => 2, 'db' => "roles.name"),
    );

    public function __construct()
    {
        $this->connect_db();
    }

    var $table = 'users LEFT JOIN roles ON roles.role_id = users.role_id';

    function getDTManagers($input)
    {
        $where = "users.role_id IN (" . ROLE_SALES_MANAGER . ', ' . ROLE_OPERATING_MANAGER . ') AND users.is_deleted = 0';

        $this->sspComplex($this->table, "user_id", $this->manager_columns, $input, null, $where);
    }

    function getDTSupport($input)
    {
        $where = "users.role_id IN (" . ROLE_ACCOUNTANT . ', ' . ROLE_WAREHOUSE . ', ' . ROLE_ADMIN .  ') AND users.is_deleted = 0';

        $this->sspComplex($this->table, "user_id", $this->support_columns, $input, null, $where);
    }

    function addUser($first_name, $last_name, $role_id, $login, $password)
    {
        $existing_user = $this->getFirst("SELECT * FROM users WHERE login = $login");
        if ($existing_user != null) {
            return false;
        } else {
            $password = md5($password);
            return $this->insert("INSERT INTO users (login, password, first_name, last_name, role_id)
                VALUES ('$login', '$password', '$first_name', '$last_name', $role_id)");
        }
    }
}