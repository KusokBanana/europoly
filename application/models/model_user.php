<?php

class ModelUser extends Model
{

    public function __construct()
    {
        $this->connect_db();
    }

    public function initializeUser()
    {

//        if (isset($_SESSION['user']) && $_SESSION['user']) {
//
//            return $_SESSION['user'];
//
//        } else {

            $userId = isset($_SESSION['user_id']) && $_SESSION['user_id'] ? $_SESSION['user_id'] : false;
            if (!$userId)
                return new MongoDB\Driver\Exception\AuthenticationException('No user identified!');

            $query = "SELECT users.*, roles.permissions as permissions 
                        FROM users LEFT JOIN roles ON (roles.role_id = users.role_id) 
                        WHERE user_id = " . $userId;

            if ($result = $this->query($query)) {
                if ($result->num_rows > 0) {
                    return $_SESSION['user'] = $result->fetch_object();
                }
            }

            return new MongoDB\Driver\Exception\AuthenticationException('No such User Identified!');

//        }

    }

}
