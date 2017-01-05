<?php

class ModelLogin extends Model
{
    function __construct()
    {
        $this->connect_db();
    }

    function getUserByEmailAndPassword($login, $hash)
    {
        return $this->getFirst("SELECT * FROM users WHERE login = '" . $this->escape_string($login) . "' AND password = '" . $this->escape_string($hash) . "'");
    }
}
