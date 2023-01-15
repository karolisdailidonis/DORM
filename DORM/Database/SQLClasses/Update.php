<?php
namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Update
{
    private $table;

    private $columns = [];

    private $conditions = [];

    private $where = null;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function set(string $column, string $value): self
    {
        $this->columns[] = $column . " = " . "'" . $value ."'";
        return $this;
    }

    public function where($var): self
    {
        $this->where = new Where($var);
        return $this;
    }

    public function __toString()
    {
        return DBHandler::getInstance()->dbTypeExecute( 
             mysql: fn() => "UPDATE " . $this->table
                . " SET " . implode( ", ", $this->columns )
                . ( $this->where === null  ?  " " : $this->where ),

             mssql: fn() => "UPDATE " . $this->table
                . " SET " . implode( ", ", $this->columns )
                . ( $this->where === null  ?  " " : $this->where )
        );
    }
}