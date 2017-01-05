<?php

class ModelClient extends Model
{

    public function __construct()
    {
        $this->connect_db();
    }

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
                        if (isset($frame[$key])) {
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
        $new_value = mysql_escape_string($new_value);
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
                $value = $value != "" ? $this->escape_string($value) : '';
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
                            $value = trim($value);
                            if (!$value)
                                continue;
                            $value = mysql_escape_string($value);
                            $setArray[] = "$name = '$value'";
                        }
                        $set = join(', ', $setArray);
                        $this->update("UPDATE client_additions 
                          SET $set, client_id = $clientId, type = '$baseType' WHERE id = $pk");
                    } else {
                        foreach ($clientAddition as $name => $value) {
                            $value = trim($value);
                            if (!$value)
                                continue;
                            $value = mysql_escape_string($value);
                            $names .= $name . ', ';
                            $values .= "'$value', ";
                        }
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
            case 'contact-persons':
                return 'Contact Persons';
            case 'bank-accounts':
                return 'Bank Accounts';
            case 'contracts':
                return 'Contracts';
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


}