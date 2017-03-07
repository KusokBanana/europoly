<?php

class ModelSupport extends Model
{
    function __construct()
    {
        $this->connect_db();
    }

    function updatePersonalInfo($user_id, $first_name, $last_name, $date_of_birth, $position, $work_phone, $mobile_number, $email, $employment_date, $notes, $roleId = false)
    {
        $set = ($date_of_birth != 'null' ? "date_of_birth = '$date_of_birth', " : "") .
            ($position != 'null' ? "position = '$position', " : "") .
            ($work_phone != 'null' ? "work_phone = '$work_phone', " : "") .
            ($mobile_number != 'null' ? "mobile_number = '$mobile_number', " : "") .
            ($email != 'null' ? "email = '$email', " : "") .
            ($employment_date != 'null' ? "employment_date = '$employment_date', " : "") .
            ($notes != 'null' ? "notes = '$notes', " : "");
        if ($roleId)
            $set .= ($roleId != 'null' ? "role_id = $roleId, " : "");
        return $this->update("UPDATE users SET " .
             $set .
            "first_name = '$first_name', last_name = '$last_name' WHERE user_id = " . $user_id);
    }

    function updateSalarySettings($user_id, $salary)
    {
        return $this->update("UPDATE users SET salary = $salary
            WHERE user_id = $user_id");
    }

    function updateAvatar($user_id, $avatar_url)
    {
        return $this->update("UPDATE users SET avatar_url = '$avatar_url'
            WHERE user_id = $user_id");
    }

    function updateAccount($user_id, $login, $password)
    {
        return $this->update("UPDATE users SET login = '$login', password = '$password'
            WHERE user_id = $user_id");
    }
}