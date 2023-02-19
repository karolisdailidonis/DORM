<?php
namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Delete
{
    private $table;

    private $where = null;

    private $sqlType = null;

    public function __construct(string $table, string $sqlType)
    {
        $this->table = $table;
        $this->sqlType = $sqlType;
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
                return "DELETE FROM " . $this->table
                    . ($this->where === null  ?  " " : $this->where);

            case 'mssql':
                return "DELETE FROM " . $this->table
                . ($this->where === null  ?  " " : $this->where);

            default:
                return "NO SQL TYPE";
        }
    }
}
