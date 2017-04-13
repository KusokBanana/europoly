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
                if ($rowIndex < 6) {
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
                'design_buro' => ['val' => returnElement($item, 'design_buro'), 'type' => 'string'],
                'second_name' => ['val' => returnElement($item, 'second_name'), 'type' => 'string'],
                'final_name' => ['val' => returnElement($item, 'final_name'), 'type' => 'string'],
                'client_category_2' => ['val' => returnElement($item, 'client_category_2'), 'type' => 'string'],
                'is_agree_for_formation' => ['val' => returnElement($item, 'is_agree_for_formation'), 'type' => 'bool'],
                'quantity_of_people' => ['val' => returnElement($item, 'quantity_of_people'), 'type' => 'int'],
                'main_target' => ['val' => returnElement($item, 'main_target'), 'type' => 'string'],
                'showrooms' => ['val' => returnElement($item, 'showrooms'), 'type' => 'bool'],
                'main_competiter' => ['val' => returnElement($item, 'main_competiter'), 'type' => 'string'],
                'samples_position' => ['val' => returnElement($item, 'samples_position'), 'type' => 'string'],
                'needful_actions' => ['val' => returnElement($item, 'needful_actions'), 'type' => 'string'],
                'countries.country_id' => ['val' => returnElement($item, 'countries.country_id'), 'type' => 'int'],
                'regions.region_id' => ['val' => returnElement($item, 'regions.region_id'), 'type' => 'int'],
                'city' => ['val' => returnElement($item, 'city'), 'type' => 'string'],
                'legal_address' => ['val' => returnElement($item, 'legal_address'), 'type' => 'string'],
                'actual_address' => ['val' => returnElement($item, 'actual_address'), 'type' => 'string'],
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
        case 'Comission Agent????':
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
        case 'Design buro/ name of the company':
            $header[$cellNumber] = 'design_buro';
            break;
        case 'Second name':
            $header[$cellNumber] = 'second_name';
            break;
        case ' FINAL NAME':
            $header[$cellNumber] = 'final_name';
            break;
        case 'Category 2 (Arch or Designer bureau/Designer-freelancer/trading agent)':
            $header[$cellNumber] = 'client_category_2';
            break;
        case 'agree to receive in formation by e-mail':
            $header[$cellNumber] = 'is_agree_for_formation';
            break;
        case 'Qty of people':
            $header[$cellNumber] = 'quantity_of_people';
            break;
        case 'Main target':
            $header[$cellNumber] = 'main_target';
            break;
        case 'Showrooms (yes / no)':
            $header[$cellNumber] = 'showrooms';
            break;
        case 'Main Competitor':
            $header[$cellNumber] = 'main_competiter';
            break;
        case 'Samples Position':
            $header[$cellNumber] = 'samples_position';
            break;
        case 'Needful Actions':
            $header[$cellNumber] = 'needful_actions';
            break;
        case 'Country':
            $header[$cellNumber] = 'countries.country_id'; // TODO linked table
            break;
        case 'Region':
            $header[$cellNumber] = 'regions.region_id'; // TODO linked table
            break;
        case 'City':
            $header[$cellNumber] = 'city';
            break;
        case 'Legal Address':
            $header[$cellNumber] = 'legal_address';
            break;
        case 'Actual Address':
            $header[$cellNumber] = 'actual_address';
            break;
    }

}