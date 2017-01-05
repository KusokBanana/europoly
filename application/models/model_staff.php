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
        $this->sspComplex("users", "user_id", $this->manager_columns, $input, null, "role = 'Sales Manager'");
    }

    function getDTSupport($input)
    {
        $this->sspComplex("users", "user_id", $this->support_columns, $input, null, "role = 'Support'");
    }

    function addUser($first_name, $last_name, $role, $login, $password)
    {
        $existing_user = $this->getFirst("SELECT * FROM users WHERE login = $login");
        if ($existing_user != null) {
            return false;
        } else {
            return $this->insert("INSERT INTO users (login, password, first_name, last_name, role)
                VALUES ('$login', '$password', '$first_name', '$last_name', '$role')");
        }
    }
}