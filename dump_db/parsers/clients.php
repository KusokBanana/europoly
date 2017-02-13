<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
date_default_timezone_set('Europe/London');
set_time_limit(-1);
ini_set('memory_limit', '-1');

function getXLS($xls){
    require_once dirname(__FILE__) . "/../../assets/phpExcel/Classes/PHPExcel/IOFactory.php";
    $file = dirname(__FILE__) . '/../raw_data/' . $xls;
    $objPHPExcel = PHPExcel_IOFactory::load($file);
    $objPHPExcel->setActiveSheetIndex(0);
//    $aSheet = $objPHPExcel->getActiveSheet();

    function returnElement($massive, $key) {
        return (isset($massive[$key]) && $massive[$key]) ? $massive[$key] : '';
    }

    function getAdditionsArray($array, $keys) {
        for ($i=0; $i < count($array); $i++) {
            $array[$keys[$i]] = $array[$i];
            unset($array[$i]);
        }
        return $array;
    }

    $sheets = $objPHPExcel->getAllSheets();

    //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
    $array = array();
    foreach ($sheets as $aSheet) {
        //получим итератор строки и пройдемся по нему циклом
        $header = [];
        foreach ($aSheet->getRowIterator() as $row) {
            //получим итератор ячеек текущей строки
            $cellIterator = $row->getCellIterator();
            $rowIndex = $row->getRowIndex();

            //пройдемся циклом по ячейкам строки
            //этот массив будет содержать значения каждой отдельной строки
            $item = array();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                //заносим значения ячеек одной строки в отдельный массив
                $value = $cell->getFormattedValue();
                $colNumber = $cell->getColumn();
                if ($rowIndex < 2) {
                    setHeader($header, $value, $colNumber);
                } else {
                    if (isset($header[$colNumber]) && $headerName = $header[$colNumber]) {
                        $arrayExplode = explode('.', $headerName);
                        if (isset($arrayExplode[1]) && $arrayExplode[1]) {
                            if ($value) {
                                $valuesArray = explode('/', $value);
                                switch ($arrayExplode[1]) {
                                    case 'contact_persons':
                                        $value = getAdditionsArray($valuesArray,
                                            ['full_name', 'position', 'phone_number', 'email']);
                                        break;
                                    case 'contracts':
                                        $value = getAdditionsArray($valuesArray,
                                            ['organization', 'contract_name', 'contract_type', 'mutual_settlements', 'contract_currency']);
                                        break;
                                }
                            } else {
                                $value = [];
                            }
                        }
                        $item[$headerName] = $value;
                    }
                }
            }

            $baseArrayItem = [
                'type' => ['val' => returnElement($item, 'type'), 'type' => 'string'],
                'clients.commission_agent_id' => ['val' => returnElement($item, 'clients.commission_agent_id'), 'type' => 'string'],
                'client_category' => ['val' => returnElement($item, 'client_category'), 'type' => 'string'],
                'name' => ['val' => returnElement($item, 'name'), 'type' => 'string'],
                'legal_name' => ['val' => returnElement($item, 'legal_name'), 'type' => 'string'],
                'inn' => ['val' => returnElement($item, 'inn'), 'type' => 'string'],
                'source' => ['val' => returnElement($item, 'source'), 'type' => 'string'],
                'status' => ['val' => returnElement($item, 'status'), 'type' => 'string'],
                'users.sales_manager_id' => ['val' => returnElement($item, 'users.sales_manager_id'), 'type' => 'string'],
                'comments' => ['val' => returnElement($item, 'comments'), 'type' => 'string'],
                'email' => ['val' => returnElement($item, 'email'), 'type' => 'string'],
                'mobile_number' => ['val' => returnElement($item, 'phone_number'), 'type' => 'string'],
                'first_contact_date' => ['val' => returnElement($item, 'first_contact_date'), 'type' => 'date'],
                'client_additions.Contracts' => ['val' => returnElement($item, 'client_additions.contracts'), 'type' => 'array'],
                'client_additions.Bank Accounts' => ['val' => returnElement($item, 'client_additions.bank_accounts'), 'type' => 'string'],
                'client_additions.Contact Persons' => ['val' => returnElement($item, 'client_additions.contact_persons'), 'type' => 'array'],
            ];

            //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
            if ($rowIndex >= 2)
                array_push($array, $baseArrayItem);
        }
    }
    return $array;
}

function setHeader(&$header, $value, $cellNumber)
{
    switch ($value) {
        case 'Category (End-Customer / Comission Agent / Dealer)':
            $header[$cellNumber] = 'type';
            break;
        case 'Comission Agent':
            $header[$cellNumber] = 'clients.commission_agent_id';
            break;
        case 'Client Category (Legal entity / physical person)':
            $header[$cellNumber] = 'client_category';
            break;
        case 'Name':
            $header[$cellNumber] = 'name';
            break;
        case 'Legal name':
            $header[$cellNumber] = 'legal_name';
            break;
        case 'INN':
            $header[$cellNumber] = 'inn';
            break;
        case 'Source of information (How did you find this contact)':
            $header[$cellNumber] = 'source';
            break;
        case 'Status (potential / active / passive)':
            $header[$cellNumber] = 'status';
            break;
        case 'Responsible Manager':
            $header[$cellNumber] = 'users.sales_manager_id';
            break;
        case 'Comments':
            $header[$cellNumber] = 'comments';
            break;
        case 'Email':
            $header[$cellNumber] = 'email';
            break;
        case 'Phone number':
            $header[$cellNumber] = 'mobile_number';
            break;
        case 'Date of first Contact':
            $header[$cellNumber] = 'first_contact_date';
            break;
        case 'Contact persons (STRICT template: Full_name / Position / Phone numbers / E-mail) if few separate with commas':
            $header[$cellNumber] = 'client_additions.contact_persons';
            break;
        case 'Bank Accounts':
            $header[$cellNumber] = 'client_additions.bank_accounts';
            break;
        case '"Contracts (STRICT template: evropoly_contract_organisation / Name_of_contract / Contract_type / mutual_settlments_type / Currency_of_the_contract) 
Evropoly_contract_organisation: Tektona/Avena, Contract_type:Client/Supplier/Other, Mutual_Settlements_type: On the orders/ On the bills / For the entire contract, Currency: USD / EURO / Rubles"':
            $header[$cellNumber] = 'client_additions.contracts';
            break;
    }

}