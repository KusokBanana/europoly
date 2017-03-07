<?php

class ModelSales_manager extends Model
{
    function __construct()
    {
        $this->connect_db();
    }

    function getDTClients($user_id, $input)
    {
        $columns = [
            array('dt' => 0, 'db' => "clients.client_id"),
            array('dt' => 1, 'db' => "clients.name"),
            array('dt' => 2, 'db' => "clients.type"),
            array('dt' => 3, 'db' => "''"),
            array('dt' => 4, 'db' => "''"),
            array('dt' => 5, 'db' => "''")
        ];
        return $this->sspComplex("clients", "clients.client_id", $columns, $input, null, "clients.sales_manager_id = $user_id");
    }

    function getDTOrders($user_id, $input)
    {
        $db = "orders 
                left join clients client on (orders.commission_agent_id = client.client_id) 
                left join clients commission on (orders.client_id = commission.client_id)
                left join items_status status on (orders.order_status_id = status.status_id)";

        $columns = [
            array('dt' => 0, 'db' => "orders.order_id"),
            array('dt' => 1, 'db' => "CONCAT('<a href=\"\order?id=', orders.order_id, '\">Order #', orders.order_id, '</a>')"),
            array('dt' => 2, 'db' => "orders.start_date"),
            array('dt' => 3, 'db' => "status.name"),
            array('dt' => 4, 'db' => "orders.special_expenses"),
            array('dt' => 5, 'db' => "orders.total_price"),
            array('dt' => 6, 'db' => "orders.downpayment_rate"),
            array('dt' => 7, 'db' => "orders.manager_bonus"),
            array('dt' => 8, 'db' => "orders.commission_rate"),
            array('dt' => 9, 'db' => "CONCAT('<a href=\"\client?id=', orders.commission_agent_id, '\">', commission.name, '</a>')"),
            array('dt' => 10, 'db' => "CONCAT('<a href=\"\client?id=', orders.client_id, '\">', client.name, '</a>')"),
            array('dt' => 11, 'db' => "orders.total_commission"),
            array('dt' => 12, 'db' => "orders.commission_status")
        ];
        return $this->sspComplex($db, "orders.order_id", $columns, $input, null, "orders.sales_manager_id = $user_id");
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
            $set.
            "first_name = '$first_name', last_name = '$last_name' WHERE user_id = $user_id");
    }

    function updateSalarySettings($user_id, $salary, $salary_bonus_rate)
    {
        return $this->update("UPDATE users SET salary = $salary, salary_bonus_rate = $salary_bonus_rate WHERE user_id = $user_id");
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

    function addOrder($sales_manager_id, $client_id)
    {
        $client = $this->getFirst("SELECT * FROM clients WHERE client_id = " . $client_id);
        $manager = $this->getFirst("SELECT * FROM users WHERE user_id = " . $sales_manager_id);
        $commission_agent_id = $client['commission_agent_id'] != '' ? $client['commission_agent_id'] : 'null';
        $time = date("Y-m-d H:i:s");
        $salaryManagerBonus = $manager['salary_bonus_rate'] ? $manager['salary_bonus_rate'] : 0;
        return $this->insert("INSERT INTO orders (start_date, expected_date_of_issue, special_expenses, total_price, total_downpayment, downpayment_rate, sales_manager_id, manager_bonus, manager_bonus_rate, commission_agent_id, commission_rate, total_commission, commission_status, client_id, email, city, mobile_number, order_items_count, cancel_reason)
                VALUES ('$time', null, 0, 0, 0, 0, $sales_manager_id, 0, $salaryManagerBonus, $commission_agent_id, 0, 0, 'Not Payed', $client_id, '${client['email']}', '${client['city']}', '${client['mobile_number']}', 0, null)");
    }
}