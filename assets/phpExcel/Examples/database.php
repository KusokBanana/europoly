<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

//require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';

$file = dirname(__FILE__) . '/../base.xlsx';

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

/** PHPExcel_IOFactory */
include dirname(__FILE__) . '/../Classes/PHPExcel.php';

try{
    $inputFileType = PHPExcel_IOFactory::identify($file);
    echo 'File ',pathinfo($file,PATHINFO_BASENAME),' has been identified as an ',$inputFileType,' file<br />';

    echo 'Loading file ',pathinfo($file,PATHINFO_BASENAME),' using IOFactory with the identified reader type<br />';
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($file);

} catch (Exception $e)
{
    die($e->getMessage());
}

/** @var PHPExcel_IOFactory $objPHPExcel */
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestCol = $sheet->getHighestColumn();

for ($row = 1; $row <= $highestRow; $row++) {
    /*            $rowData = $sheet->rangeToArray('A'.$row.':'.$highestCol.$row, NULL, TRUE, FALSE);
                $brand = $rowData[0][0];
                $type = $rowData[0][1];
                $artnumber = $rowData[0][2];
                $name = $rowData[0][3];
                $link = $rowData[0][3];*/
    $color = $sheet->getCell('Z'.$row)->getValue();
    $construction = $sheet->getCell('AI'.$row)->getValue();
    echo '<pre>';
    var_dump($color);
    var_dump($construction);
    echo '</pre>';
    die();

}