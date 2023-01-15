<?php
namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Delete
{
    private $table;

    private $where = null;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function where($var): self
    {
        $this->where = new Where($var);
        return $this;
    }

    public function __toString()
    {
        return DBHandler::getInstance()->dbTypeExecute( 
            mysql: fn() => "DELETE FROM " . $this->table
                . ($this->where === null  ?  " " : $this->where ),

            mssql: fn() => "DELETE FROM " . $this->table
                . ($this->where === null  ?  " " : $this->where )
        );
    }
}
