<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');
require_once dirname(__FILE__) . '/../../assets/phpExcel/Classes/PHPExcel.php';


class Excel extends PHPExcel {

    private $dir = '/docs/table_excels/';

    public function printTable($data, $allowed, $fileName)
    {

       $this->getProperties()->setCreator("Maarten Balliauw")
           ->setLastModifiedBy("Maarten Balliauw")
           ->setTitle("Office 2007 XLSX Test Document")
           ->setSubject("Office 2007 XLSX Test Document")
           ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
           ->setKeywords("office 2007 openxml php")
           ->setCategory("Test result file");
       $this->setActiveSheetIndex(0);

//       $data = array_slice($data, 0, 100);

       // filter array for only visible values
       foreach ($data as $key => $array) {
           $replaced = [];
           $filtered = array_filter(
               $array,
               function ($k) use ($allowed, $array, &$replaced) {
                   if (in_array($k, $allowed)) {
                       $value = $array[$k];
                       preg_match('/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i', $value, $match);
                       if (!empty($match) && isset($match[1])) {
                           $replaced[$k] = $match[1];
                       }
                       return true;
                   }
                   return false;
               },
               ARRAY_FILTER_USE_KEY
           );

           foreach ($replaced as $replace_key => $replace_value) {
               if (isset($filtered[$replace_key]))
                   $filtered[$replace_key] = $replace_value;
           }

           $data[$key] = $filtered;
         }

         $this->getActiveSheet()->fromArray($data);

         $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');

         $dir = dirname(__FILE__) . '/../..';
         $name = $this->dir . $_SESSION['user_role'] . '_' . $fileName . '.xls';
         $file = $dir . $name;

         $objWriter->save($file);

         return $name;
    }

}
