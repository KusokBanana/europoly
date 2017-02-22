<?php

class ModelStaff extends Model
{
    var $manager_columns = array(
        array('dt' => 0, 'db' => "users.user_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"\sales_manager?id=', users.user_id, '\">', users.first_name, ' ', users.last_name, '</a>')"),
        array('dt' => 2, 'db' => "'a'"),
        array('dt' => 3, 'db' => "'b'"),
        array('dt' => 4, 'db' => "'c'"),
        array('dt' => 5, 'db' => "'d'"),
        array('dt' => 6, 'db' => "'e'")
    );

    var $support_columns = array(
        array('dt' => 0, 'db' => "users.user_id"),
        array('dt' => 1, 'db' => "CONCAT('<a href=\"\support?id=', users.user_id, '\">', users.first_name, ' ', users.last_name, '</a>')"),
        array('dt' => 2, 'db' => "users.position"),
        array('dt' => 3, 'db' => "users.salary")
    );

    public function __construct()
    {
        $this->connect_db();
    }

    function getDTManagers($input)
    {
        $this->sspComplex("users", "user_id", $this->manager_columns, $input, null, "role_id = " . ROLE_SALES_MANAGER);
    }

    function getDTSupport($input)
    {
        $where = "role_id IN (" . ROLE_ACCOUNTANT . ', ' . ROLE_WAREHOUSE . ')';
        $this->sspComplex("users", "user_id", $this->support_columns, $input, null, $where);
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

    function getRoles()
    {
        return $this->getAssoc("SELECT role_id as id, name FROM roles WHERE role_id != ".ROLE_ADMIN);
    }
}