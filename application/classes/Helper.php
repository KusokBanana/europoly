<?php

/**
 * Created by PhpStorm.
 * User: kusok
 * Date: 01.06.2017
 * Time: 12:42
 */
class Helper
{

    public static function arrGetVal($arr, $index)
    {
        return isset($arr[$index]) ? $arr[$index] : false;
    }

    public static function varDump($array, $isWithTypes = false)
    {
        echo '<br><pre>';
        if ($isWithTypes)
            var_dump($array);
        else
            print_r($array);
        echo '</pre><br>';
    }

    public static function safeVar($var)
    {
        if (!$var || is_null($var))
            return false;

        $var = trim($var);
//        $var = mysql_real_escape_string($var);
//        $var = htmlspecialchars($var);
        $var = addslashes($var);
//        $var = htmlspecialchars($var);
        $var = strip_tags($var);
        return $var;
    }

}