<?php

class ModelClient extends Model
{

    public function __construct()
    {
        $this->connect_db();
    }

    var $requests = [
        'date' => ['type' => 'date', 'cols' => 3, 'label' => 'Date'],
        'description' => ['type' => 'input', 'cols' => 9, 'label' => 'Description']

    ];

    var $contactPersonsNames = [
        'full_name' => ['type' => 'input', 'cols' => 3, 'label' => 'Full Name'],
        'position' => ['type' => 'input', 'cols' => 3, 'label' => 'Position'],
        'phone_number' => ['type' => 'input', 'cols' => 3, 'label' => 'Phone numbers'],
        'email' => ['type' => 'input', 'cols' => 3, 'label' => 'E-mail'],

    ];

    var $bankAccountsNames = [
        'requisites' => ['type' => 'input', 'cols' => 12, 'label' => '', 'placeholder' => 'Fill in Bank Account'],
    ];

    var $contractsNames = [
        'organization' => ['type' => 'select', 'cols' => 3, 'label' => 'Organization',
            'values' => [
                [
                    'value' => '',
                    'text' => 'Список собственных организаций Европолов'
                ],
            ],
        ],
        'contract_name' => ['type' => 'input', 'cols' => 3, 'label' => 'Name of the Contract'],
        'contract_type' => ['type' => 'select', 'cols' => 2, 'label' => 'Contract type',
            'values' => [
                [
                    'value' => '',
                    'text' => ''
                ],
                [
                    'value' => 'Client',
                    'text' => 'Client'
                ],
                [
                    'value' => 'Supplier',
                    'text' => 'Supplier'
                ],
                [
                    'value' => 'Other',
                    'text' => 'Other'
                ],
            ],
        ],
        'mutual_settlements' => ['type' => 'select', 'cols' => 2, 'label' => 'Mutual settlements',
            'values' => [
                [
                    'value' => '',
                    'text' => ''
                ],
                [
                    'value' => 'On the orders',
                    'text' => 'On the orders'
                ],
                [
                    'value' => 'On the bills',
                    'text' => 'On the bills'
                ],
                [
                    'value' => 'For the entire contract',
                    'text' => 'For the entire contract'
                ],
            ],
        ],
        'contract_currency' => ['type' => 'select', 'cols' => 2, 'label' => 'Currency of the contract',
            'values' => [
                [
                    'value' => '',
                    'text' => ''
                ],
                [
                    'value' => 'USD',
                    'text' => 'USD'
                ],
                [
                    'value' => 'EURO',
                    'text' => 'EURO'
                ],
                [
                    'value' => 'Rubles',
                    'text' => 'Rubles'
                ],
            ],
        ],
    ];

    public function getFrame($frameName)
    {
        $frame = $this->$frameName;
        if (isset($frame['organization'])) {
            $frame['organization']['values'] = $this->getLegalEntitiesForList();
        }
        return $frame;
    }

    public function getRequests($clientId)
    {
        return $this->getAssoc("SELECT date, description, id FROM client_additions 
          WHERE client_id = $clientId AND type = 'Requests'");
    }

    public function getContactPersons($clientId)
    {
        return $this->getAssoc("SELECT full_name, position, phone_number, email, id FROM client_additions 
          WHERE client_id = $clientId AND type = 'Contact Persons'");
    }

    public function getBankAccounts($clientId)
    {
        return $this->getAssoc("SELECT requisites, id FROM client_additions 
          WHERE client_id = $clientId AND type = 'Bank Accounts'");
    }

    public function getContracts($clientId)
    {
        return $this->getAssoc("SELECT organization, contract_name, contract_type, mutual_settlements, contract_currency, id
          FROM client_additions WHERE client_id = $clientId AND type = 'Contracts'");
    }

    public function getLegalEntitiesForList()
    {
        $entities = $this->getAssoc("SELECT legal_entity_id as id, name FROM legal_entities");
        $list = [];
        if (!empty($entities))
            foreach ($entities as $entity) {
                $item = [
                    'value' => $entity['id'],
                    'text' => $entity['name']
                ];
                $list[] = $item;
            }
        return $list;
    }

    public function buildForm($type)
    {
        $form = [];
        switch ($type) {
            case 'requests':
                $form['requests'] = $this->getFrame('requests');
                break;
            case 'contact-persons':
                $form['contact-persons'] = $this->getFrame('contactPersonsNames');
                break;
            case 'bank-accounts':
                $form['bank-accounts'] = $this->getFrame('bankAccountsNames');
                break;
            case 'contracts':
                $form['contracts'] = $this->getFrame('contractsNames');
                break;
            case 'all':
                $form['requests'] = $this->getFrame('requests');
                $form['contact-persons'] = $this->getFrame('contactPersonsNames');
                $form['bank-accounts'] = $this->getFrame('bankAccountsNames');
                $form['contracts'] = $this->getFrame('contractsNames');
                break;
        }
        return $form;
    }

    public function buildPrimaryForm($clientId)
    {
        $base = [
            'requests' => [$this->getRequests($clientId), 'requests'],
            'contactPersonsNames' => [$this->getContactPersons($clientId), 'contact-persons'],
            'bankAccountsNames' => [$this->getBankAccounts($clientId), 'bank-accounts'],
            'contractsNames' => [$this->getContracts($clientId), 'contracts']

        ];
        $forms = [];
        foreach ($base as $frameName => $table) {
            if ($table[0] && !empty($table[0])) {
                $i=0;
                foreach ($table[0] as $tableValues) {
                    $frame = $this->getFrame($frameName);
                    foreach ($tableValues as $key => $value) {
                        if ($key == 'id') {
                            $frame['pk'] = ['value' => $value];
                        }
                        if (isset($frame[$key]) && $value) {
                            $frame[$key]['value'] = $value;
                        }
                    }
                    $frameNameString = $table[1];
                    $forms[$i][$frameNameString] = $frame;
                    $i++;
                }
            }
        }
        return $forms;
    }

    public function updateItemField($client_id, $field, $new_value)
    {
        if (is_string($new_value))
            $new_value = Helper::safeVar($new_value);
        else
            $new_value = mysql_escape_string($new_value);
        // TODO maybe need to remove else condition
        $result = $this->update("UPDATE clients SET `$field` = '$new_value' WHERE client_id = $client_id");
        return $result;
    }

    public function updateClient($fields, $clientId)
    {
        if (!empty($fields))
            foreach ($fields as $field => $value) {
                $this->updateItemField($clientId, $field, $value);
            }
    }

    public function createClient($fields)
    {
        if (!empty($fields)) {
            $valuesArray = [];
            $fieldsArray = [];
            foreach ($fields as $field => $value) {
                $value = Helper::safeVar($value);
                if (!$value) continue;
                $fieldsArray[] = "$field";
                $valuesArray[] = "'$value'";
            }
            $values = join(', ', $valuesArray);
            $fieldsString = join(', ', $fieldsArray);
            $id = $this->insert("INSERT INTO clients ($fieldsString) VALUES ($values)");
            return $id;
        }
    }

    public function updateClientAdditions($clientAdditions, $clientId)
    {
        foreach ($clientAdditions as $type => $clientAdditionArray) {
            if (!empty($clientAdditionArray)) {
                foreach ($clientAdditionArray as $clientAddition) {

                    $values = '';
                    $names = '';
                    $baseType = $this->translateType($type);

                    if (isset($clientAddition['pk']) && $pk = $clientAddition['pk']) {
                        unset($clientAddition['pk']);
                        $setArray = [];
                        foreach ($clientAddition as $name => $value) {
                            $value = Helper::safeVar($value);
                            if (!$value)
                                continue;
                            $setArray[] = "$name = '$value'";
                        }

                        if (empty($setArray))
                            continue;

                        $set = join(', ', $setArray);
                        $this->update("UPDATE client_additions 
                          SET $set, client_id = $clientId, type = '$baseType' WHERE id = $pk");
                    } else {
                        foreach ($clientAddition as $name => $value) {
                            $value = Helper::safeVar($value);
                            if (!$value)
                                continue;
                            $names .= $name . ', ';
                            $values .= "'$value', ";
                        }

                        if (!$values)
                            continue;

                        $this->insert("INSERT INTO client_additions ($names client_id, type)
                          VALUES ($values $clientId, '$baseType')");
                    }
                }
            }
        }
    }

    public function deleteClientAddition($pk)
    {
        $this->delete("DELETE FROM client_additions WHERE id = $pk");
    }

    public function checkFields($client, $addition, $clientId)
    {

        $tableClient = $this->getFirst("SELECT * FROM clients 
          WHERE client_id = $clientId");
        foreach ($client as $name => $value) {
            if ($value != $tableClient[$name])
                return true;
        }

// TODO добавить проверку доп. полей
        foreach ($addition as $pk => $item) {

        }

    }

    public function translateType($type)
    {
        switch ($type) {
            case 'requests':
                return 'Requests';
            case 'contact-persons':
                return 'Contact Persons';
            case 'bank-accounts':
                return 'Bank Accounts';
            case 'contracts':
                return 'Contracts';
            case 'Requests':
                return 'requests';
            case 'Contact Persons':
                return 'contact-persons';
            case 'Bank Accounts':
                return 'bank-accounts';
            case 'Contracts':
                return 'contracts';
        }
        return $type;
    }

    public function getCountryAndRegionNamesByIds($country_id, $regionId)
    {
        $regionName = '';
        $countryName = '';
        if ($country_id) {
            $country = $this->getFirst("SELECT name FROM countries WHERE country_id = $country_id");
            $regionName = $country['name'];
            if ($regionId) {
                $region = $this->getFirst("SELECT name FROM regions WHERE country_id = $country_id 
                                              AND region_id = $regionId");
                $countryName = $region['name'];
            }
        }
        return [
            'country' => $regionName,
            'region' => $countryName
        ];
    }

    public function importClients($array, $clear)
    {

        if ($clear) {
            $this->clearTable('clients');
            $this->clearTable('client_additions');
            $this->clearIncrement('clients');
            $this->clearIncrement('client_additions');
        }

        $entities = $this->getAssoc("SELECT * FROM legal_entities");

        function getEntityId($entityStr, $entities, $client) {
            $entityId = false;
            foreach ($entities as $entity) {
                if ($entity['name'] == $entityStr) {
                    $entityId = $entity['legal_entity_id'];
                }
            }
            if (!$entityId) {
                $entityId = $client->insert("INSERT INTO legal_entities (name) VALUES ('$entityStr')");
            }
            return $entityId;
        }

        $commissionAgents = [];
        $countries = [];

        foreach ($array as $key => $item) {

            $names = '';
            $values = '';
            $commissionAgent = '';
            $additions = [];

            foreach ($item as $name => $valsArray) {
                $type = $valsArray['type'];
                $value = $valsArray['val'];

                if ($type != 'array') {
                    $value = trim($value);
                    $value = mysql_real_escape_string($value);
                }

                if (!$value)
                    continue;

                if (strpos($name, '.') !== false) {
                    $explodeArray = explode('.', $name);

                    // add commission agent to temp value for search
                    if ($explodeArray[1] == 'commission_agent_id') {
                        $commissionAgent = $value;
                        continue;
                    }

                    // add commission agent to temp value for search
                    if ($explodeArray[1] == 'country_id') {

                        $countryId = $this->getFirst("SELECT country_id FROM countries 
                          WHERE LOWER(name) = '" . mb_strtolower($value) . "'");
                        if ($countryId) {
                            $countryId = $countryId['country_id'];
                        } else {
                            $countryId = $this->insert("INSERT INTO countries (name) VALUES ('$value')");
                        }
                        $value = $countryId;
                        $name = $explodeArray[1];
                        $countries[$key] = $value;
                    }

                    if ($explodeArray[1] == 'region_id') {

                        $countryId = isset($countries[$key]) ? $countries[$key] : false;
                        if (!$countryId)
                            continue;

                        $regionId = $this->getFirst("SELECT region_id FROM regions 
                          WHERE country_id = $countryId AND LOWER(name) = '" . mb_strtolower($value) . "'");
                        if ($regionId) {
                            $regionId = $regionId['region_id'];
                        } else {
                            $regionId = $this->insert("INSERT INTO regions (name, country_id) VALUES ('$value', $countryId)");
                        }
                        $value = $regionId;
                        $name = $explodeArray[1];
                    }

                    if ($explodeArray[1] == 'sales_manager_id') {
                        $explodeManagerName = explode(' ', $value);
                        $managerId = $this->getFirst("SELECT user_id as id FROM users WHERE 
                          (last_name = '${explodeManagerName[0]}' AND first_name = '${explodeManagerName[1]}') OR 
                          (first_name = '${explodeManagerName[0]}' AND last_name = '${explodeManagerName[1]}')");
                        if ($managerId) {
                            $value = $managerId['id'];
                        } else {
                            $salesManagerMaxId = $this->getMax("SELECT MAX(user_id) FROM users") + 1;
                            $login = 'sales_manager_'.$salesManagerMaxId;
                            $value = $this->insert("INSERT INTO users (first_name, last_name, role_id, login, password) 
                              VALUES ('${explodeManagerName[1]}', '${explodeManagerName[0]}', 2, 
                              '$login', 'password')"); // TODO REPLACE_CONST
                        }
                        $name = $explodeArray[1];
                    }
                    if ($explodeArray[1] == 'Bank Accounts') {
                        $additionNames = "requisites, ";
                        $additionValues = "$value, ";
                        $type = $explodeArray[1];
                        $additions[$type] = [$additionNames, $additionValues];
                        continue;
                    }
                    if ($type == 'array') {
                        $additionNames = '';
                        $additionValues = '';
                        foreach ($value as $additionName => $additionValue) {
                            if (!$additionValue)
                                continue;
                            if ($additionName == 'organization') {
                                $additionValue = getEntityId($additionValue, $entities, $this);
                            }
                            $additionValues .= "'$additionValue', ";
                            $additionNames .= $additionName . ', ';
                        }
                        if ($additionNames && $additionValues) {
                            $type = $explodeArray[1];
                            $additions[$type] = [$additionNames, $additionValues];
                        }
                        continue;
                    }
                }

                $names .= $name . ', ';
                if ($type == 'string')
                    $values .= "'$value', ";
                elseif ($type == 'date') {
                    $value = (string) date('y-m-d', $value);
                    $values .= "'$value', ";
                }
                else {
                    if ($type == 'float' || $type == 'double') {
                        $value = floatval($value);
                    }
                    if ($type == 'int') {
                        $value = intval($value);
                    }
                    if ($type == 'bool') {
                        $value = in_array(mb_strtolower($value), ['yes', 'да']) ? 1 : 0;
                    }
                    $values .= "$value, ";
                }
            }

            if ($values && $names) {
                $values = substr($values, 0, -2);
                $names = substr($names, 0, -2);

                $clientId = $this->insert("INSERT INTO clients ($names)
                          VALUES ($values)");

                if ($clientId) {
                    if ($commissionAgent) {
                        $commissionAgents[$clientId] = $commissionAgent;
                    }
                    if (!empty($additions)) {
                        foreach ($additions as $additionType => $addition) {
                            $names = $addition[0];
                            $values = $addition[1];
                            $this->insert("INSERT INTO client_additions ($names type, client_id) 
                              VALUES ($values '$additionType', $clientId)");
                        }
                    }
                }
            }
        }

        if (!empty($commissionAgents)) {
            foreach ($commissionAgents as $client_id => $commissionAgent) {
                $client = $this->getFirst("SELECT client_id FROM clients WHERE name = '$commissionAgent'");
                if ($client) {
                    $commissionAgentId = $client['client_id'];
                } else {
                    $commissionAgentId = $this->insert("INSERT INTO clients (name) VALUES ('$commissionAgent')");
                }
                if ($commissionAgentId)
                    $this->update("UPDATE clients SET commission_agent_id = $commissionAgentId WHERE client_id = $client_id");
            }
        }

    }


}