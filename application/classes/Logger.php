<?php

class Logger
{
    /**
     * Returns current log file name
     * If current file is too big, creates new file
     * @param $dir string
     * @return string
     */
    private static function getFile($dir)
    {
        $file_name = '';
        $i = 1;
        while(file_exists($dir.'log'.$i.'.txt')) {
            $file_name = 'log'.$i.'.txt';
            $i++;
        }
        $file_name = $dir.$file_name;
        if (filesize($file_name) > 5000000) {
            $file_name = $dir.'log'.$i.'.txt';
            file_put_contents($file_name, '');
        }
        return $file_name;
    }

    public static function createInsert($query, $user_id, $insert_id = null)
    {
        $table_name = explode(' ', explode('INSERT INTO ', $query)[1])[0];
        if ($table_name == 'logging')
            return false;
        $success = is_null($insert_id) ? 'FAIL' : 'SUCCESS';
        $time = date('d.m.Y H:i:s');
        $result = "\n".$time.' '.$success.' table: '.$table_name.' user_id: '.$user_id.' insert_id: '.$insert_id."\n".$query;

        $file = static::getFile(dirname(__FILE__) . '/../../logs/insert/');
        $current_file_content = file_get_contents($file);
        file_put_contents($file, $current_file_content.$result);
    }

    public static function createUpdate($query, $table_name, $user_id, $record_id = null)
    {
        $success = is_null($record_id) ? 'FAIL' : 'SUCCESS';
        $time = date('d.m.Y H:i:s');
        $result = "\n".$time.' '.$success.' table: '.$table_name.' user_id: '.$user_id.' record_id: '.$record_id."\n".$query;

        $file = static::getFile(dirname(__FILE__) . '/../../logs/update/');
        $current_file_content = file_get_contents($file);
        file_put_contents($file, $current_file_content.$result);
    }

    public static function createDelete($query, $table_name, $user_id, $record_id = null)
    {
        $success = is_null($record_id) ? 'FAIL' : 'SUCCESS';
        $time = date('d.m.Y H:i:s');
        $result = "\n".$time.' '.$success.' table: '.$table_name.' user_id: '.$user_id.' record_id: '.$record_id."\n".$query;

        $file = static::getFile(dirname(__FILE__) . '/../../logs/delete/');
        $current_file_content = file_get_contents($file);
        file_put_contents($file, $current_file_content.$result);
    }
}