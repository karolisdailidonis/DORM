<?php
namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Replace
{
    private $table;
    
    private $columns = [];

    private $values = [];

    private $sqlType = null;

    public function __construct(string $table, string $sqlType)
    {
        $this->table = $table;
        $this->sqlType = $sqlType;
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function values(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    public function __toString(): string
    {
        switch ($this->sqlType) {
            case 'mysql':
                echo "REPLACE INTO " . $this->table
                . " ( " . implode(", ", $this->columns ) . " ) VALUES ( '" . implode("', '", $this->values ) . "' );";
                return "REPLACE INTO " . $this->table
                 . " ( " . implode(", ", $this->columns ) . " ) VALUES ( '" . implode("', '", $this->values ) . "' );";

            // case 'mssql':
            //     return "UPDATE " . $this->table
            //        . " SET " . implode( ", ", $this->columns )
            //        . ( $this->where === null  ?  " " : $this->where )
            //        . "IF @@ROWCOUNT=0"
            //        . "INSERT INTO " . $this->table
            //        . " ( " . implode(", ", $this->columns ) . " ) VALUES ( '" . implode("', '", $this->values ) . "' );";

            default:
                return "NO SQL TYPE";
        }
        
        
    }
}