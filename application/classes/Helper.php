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

}