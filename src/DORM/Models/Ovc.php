<?php
/*
* DORM generated class
*/

use DORM\Includes\DORMModel;

class Ovc extends DORMModel {
    
    protected $tableName = 'ovc';
    protected $columns = array(
        'person_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'NO'),
        'ovc_number' => array( 'type' => 'varchar', 'length' => 13, 'nullable' => 'YES'),
        'ovc_status' => array( 'type' => 'varchar', 'length' => 45, 'nullable' => 'YES'),
        'generate_income' => array( 'type' => 'varchar', 'length' => 45, 'nullable' => 'YES'),
        'lead_child' => array( 'type' => 'tinyint', 'length' => -1, 'nullable' => 'YES'),
        'time_sick' => array( 'type' => 'smallint', 'length' => -1, 'nullable' => 'YES'),
        'vaccinated' => array( 'type' => 'varchar', 'length' => 45, 'nullable' => 'YES'),
        'seeked_treatment' => array( 'type' => 'varchar', 'length' => 45, 'nullable' => 'YES'),
        'disability' => array( 'type' => 'varchar', 'length' => 45, 'nullable' => 'YES'),
        'birthcertification' => array( 'type' => 'varchar', 'length' => 45, 'nullable' => 'YES'),
        'who_pays_fee' => array( 'type' => 'varchar', 'length' => 100, 'nullable' => 'YES'),
        'school_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'YES'),
        'days_missed' => array( 'type' => 'smallint', 'length' => -1, 'nullable' => 'YES'),
        'class' => array( 'type' => 'varchar', 'length' => 50, 'nullable' => 'YES'),
        'caregiver_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'YES'),
        'household_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'YES'),

    );

    public function getTableName(){
        return $this->tableName;
    } 

}

?>