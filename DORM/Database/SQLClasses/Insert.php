<?php
namespace DORM\Database\SQLClasses;


class Insert {
    
    private $table;
    
    private $columns = [];

    private $values = [];


    public function __construct( string $table){
        $this->table = $table;
    }

    public function columns( array $columns ): self {
        $this->columns = $columns;
        return $this;
    }

    public function values( array $values ): self {
        $this->values = $values;
        return $this;
    }

    public function __toString(): string {
        
        $query = "INSERT INTO " . $this->table
        . " ( " . implode(", ", $this->columns ) . " ) VALUES ( '" . implode("', '",     $this->values ) . "' );";
        
        // MariaDB 10.5
        // TODO: Implement RETURNING
        
        return $query;
    }


}
