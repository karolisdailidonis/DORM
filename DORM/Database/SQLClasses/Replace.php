<?php
namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Replace
{
    private $table;
    
    private $columns = [];

    private $values = [];

    private $where = null;

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

    // where is only required for some db types like mssql
    public function where($var): self
    {
        $this->where = new Where($var);
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

            case 'mssql':
                // create the string for the columns/value pairs for the update with the columns and values arrays
                $keyvalues = '';
                foreach ($this->columns as $key => $value) {
                  $keyvalues .= $value . ' = \'' . $this->values[$key] . '\', ';
                }
                $keyvalues = rtrim($keyvalues, ', ');

                // for mssql we use an update and if no new row was added, then use an insert
                return "UPDATE " . $this->table
                   . " SET " . $keyvalues
                   . $this->where
                   . "IF @@ROWCOUNT=0"
                   . "INSERT INTO " . $this->table
                   . " ( " . implode(", ", $this->columns ) . " ) VALUES ( '" . implode("', '", $this->values ) . "' );";

            default:
                return "NO SQL TYPE";
        }
        
        
    }
}