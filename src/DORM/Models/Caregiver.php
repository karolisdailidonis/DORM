<?php
/*
* DORM generated class
*/

use DORM\Includes\DORMModel;

class Caregiver extends DORMModel {
    
    private $tableName = 'caregiver';
    private $columns = array(
        'person_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'NO'),
        'location_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'NO'),
        'occupation' => array( 'type' => 'varchar', 'length' => 100, 'nullable' => 'YES'),

    );

    public function getTableName(){
        return $this->tableName;
    } 

}

?>