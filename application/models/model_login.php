<?php

class ModelLogin extends Model
{
    function __construct()
    {
        $this->connect_db();
    }

    function getUserByEmailAndPassword($login, $hash)
    {
        return $this->getFirst("SELECT * FROM users WHERE is_deleted = 0 AND login = '" . $this->escape_string($login) .
            "' AND password = '" . $this->escape_string($hash) . "'");
    }

    function saveOrderColumns($columns, $tableId)
    {
        $userId = $this->user->user_id;
        $savedOrderColumns = $this->getFirst("SELECT columns_order FROM users WHERE user_id = $userId");
        if ($savedOrderColumns) {
            $savedOrderColumnsJson = $savedOrderColumns['columns_order'];
            if ($savedOrderColumnsJson) {
                $savedOrderColumns = json_decode($savedOrderColumnsJson, true);
                $savedOrderColumns[$tableId] = json_decode($columns, true);
            } else {
                $savedOrderColumns = [];
                $savedOrderColumns[$tableId] = json_decode($columns, true);
            }
            $savedOrderColumnsJson = json_encode($savedOrderColumns);
            return $this->update("UPDATE users SET columns_order = '$savedOrderColumnsJson' WHERE user_id = $userId");
        }
    }

    function saveRecordsCount($count, $tableId)
    {
        $userId = $this->user->user_id;
        $savedCount = $this->getFirst("SELECT records_show FROM users WHERE user_id = $userId");
        if ($savedCount) {
            $savedCountJson = $savedCount['records_show'];
            if ($savedCountJson) {
                $savedCount = json_decode($savedCountJson, true);
                $savedCount[$tableId] = json_decode($count, true);
            } else {
                $savedCount = [];
                $savedCount[$tableId] = json_decode($count, true);
            }
            $savedCountJson = json_encode($savedCount);
            return $this->update("UPDATE users SET records_show = '$savedCountJson' WHERE user_id = $userId");
        }

    }

    function saveResizeColumns($columns_widths, $tableId)
    {
        $userId = $this->user->user_id;
        $saveResizeColumns = $this->getFirst("SELECT columns_resize FROM users WHERE user_id = $userId");
        if ($saveResizeColumns) {
            $savedResizeColumnsJson = json_decode($saveResizeColumns['columns_resize'],true);
            if ($savedResizeColumnsJson) {
                $saveResizeColumns = $savedResizeColumnsJson;
                $saveResizeColumns[$tableId][$columns_widths['id']] = $columns_widths['value'];
            } else {
                $saveResizeColumns = [];
                $saveResizeColumns[$tableId][$columns_widths['id']] = $columns_widths['value'];
            }
            $savedResizeColumnsJson = json_encode($saveResizeColumns);
            return $this->update("UPDATE users SET columns_resize = '$savedResizeColumnsJson' WHERE user_id = $userId");
        }
    }

    function getResizeColumns()
    {
        $userId = $this->user->user_id;
        //$saveResizeColumns = $this->getFirst("SELECT columns_resize FROM users WHERE user_id = $userId");
        $getResizeColumns=$this->getFirst("SELECT columns_resize FROM users WHERE user_id = $userId");
        return $getResizeColumns['columns_resize'];
        //update("UPDATE users SET columns_resize = '$saveResizeColumns' WHERE user_id = $userId");
       
    }

}
