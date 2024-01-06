<?php
namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Update
{
    private $table;

    private $columns = [];

    private $conditions = [];

    // TODO: Make Required
    private $where = null;

    private $sqlType = null;

    public function __construct(string $table, string $sqlType)
    {
        $this->table = $table;
        $this->sqlType = $sqlType;
    }

    // TODO: Check $value type
    public function set(string $column, $value, $isFunc = false): self
    {
        if(($value == null || $value == 'NULL'|| $value == 'null' || is_null($value)) && gettype($value) != 'integer') {
            $this->columns[] = $column . " = NULL";
            return $this;
        }

        if ($isFunc) {
            $this->columns[] = $column . " = " . $value;
            return $this;
        }

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
        switch ($this->sqlType) {
            case 'mysql':
                return "UPDATE " . $this->table
                   . " SET " . implode( ", ", $this->columns )
                   . ( $this->where === null  ?  " " : $this->where );

            case 'mssql':
                return "UPDATE " . $this->table
                   . " SET " . implode( ", ", $this->columns )
                   . ( $this->where === null  ?  " " : $this->where );

            default:
                return "NO SQL TYPE";
        }
    }
}