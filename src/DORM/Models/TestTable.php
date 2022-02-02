<?php
namespace DORM\Models;

use DORM\Includes\DORMModel;

class TestTable extends DORMModel {
    
    public $tableName = 'person';
    private $columns = array(
        'person_id' => array( 'type' => 'int', 'length' => -1, 'nullable' => 'NO'),
        'surname' => array( 'type' => 'varchar', 'length' => 100, 'nullable' => 'NO'),
        'name' => array( 'type' => 'varchar', 'length' => 100, 'nullable' => 'NO'),
        'gender' => array( 'type' => 'char', 'length' => 1, 'nullable' => 'NO'),
        'dob' => array( 'type' => 'tinyblob', 'length' => 255, 'nullable' => 'YES'),
        'tel' => array( 'type' => 'varchar', 'length' => 15, 'nullable' => 'YES'),
        'email' => array( 'type' => 'varchar', 'length' => 100, 'nullable' => 'YES'),

    );

    function __construct(){
        
    }
    
    public function getTableName(){
        return $this->tableName;
    } 

}
