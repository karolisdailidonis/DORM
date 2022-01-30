<?php
namespace DORM\Models;

class Tempplate {
    
    private $tableName = 'blubb';

    private $columns = array(
        'person_id' => array( 'type' => 'integer', 'length' => 115, 'nullable' => false),
        'surname' => array( 'type' => 'integer', 'length' => 115, 'nullable' => false),
    );

    private $relations = array(
        'r1' => array( 'column_name' => '', 'referenced_table_name' => '', 'referenced_column_name' => ''),
        'r2' => array( 'column_name' => '', 'referenced_table_name' => '', 'referenced_column_name' => '')
    );

    public function getTableName(){
        return $this->tableName;
    } 

}

?>