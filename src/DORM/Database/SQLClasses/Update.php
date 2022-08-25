<?php
namespace DORM\Database\SQLClasses;

class Update {

    private $table;

    private $columns = [];

    private $conditions = [];

    private $where = null;

    public function __construct( string $table ){
        $this->table = $table;
    }

    public function set( string $column, string $value): self {
        
        $this->columns[] = $column . " = " . "'" . $value ."'";
        return $this;
    }


    // ToDo: Where als eigene Klasse auslagern? wird auch in select gebraucht etc.
    public function where( string $column, string $condition, string $value ): self {
        $this->where = $column . " " . $condition . "'" . $value . "'";
        return $this; 
    }

    public function __toString(){
        
        return "UPDATE " . $this->table
                . " SET " . implode( ", ", $this->columns )
                . ( $this->where === null  ?  " " : " WHERE " . $this->where );
                // . ( $this->conditions === [] ? "" : " WHERE " . implode( " AND ", $this->conditions));
    }

}

?>