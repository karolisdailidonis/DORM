<?php

namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Select
{
    private $columns = [];

    private $from = [];

    private $where = null;

    private $leftJoin = [];

    private $limit = 1000;

    private $order = null;

    private $sqlType = null;

    static private $strLeftJoin = "LEFT JOIN";

    public function __construct(array $columns = null, string $sqlType)
    {
        ($columns != null) ? $this->columns = $columns : $this->columns = array('*');
        $this->sqlType = $sqlType;
    }

    public function from(string $table, string $alias = null): self
    {
        $this->from[] = $alias === null ? $table : "${table} AS ${alias}";
        return $this;
    }

    public function where($var): self
    {
        $this->where = new Where($var);
        return $this;
    }

    public function order($var): self
    {
        $this->order = ' ORDER BY ' . $var['column'] . ' ' .   ( ( isset($var['sort']) ) ? $var['sort'] : '' );
        
        return $this;
    }

    // TODO: reduce to one pair if same ?
    // TODO: make a single class ?
    public function join(string $table1, string $table2, string $column1, string $column2 = null): self
    {
        $sql = "%TABLE2% ON %TABLE1%.%COLUMN1% = %TABLE2%.%COLUMN2%";

        $sql = str_replace("%TABLE1%", $table1, $sql);
        $sql = str_replace("%COLUMN1%", $column1, $sql);

        $sql = str_replace("%TABLE2%", $table2, $sql);
        $sql = str_replace("%COLUMN2%", $column2, $sql);

        $this->leftJoin[] = $sql;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function __toString(): string
    {
        switch ($this->sqlType) {
            case 'mysql':
                return 'SELECT ' . implode(', ', $this->columns)
                    . ' FROM ' . implode(', ', $this->from)
                    . ($this->leftJoin === [] ? '' : $this->strLeftJoin . implode($this->strLeftJoin, $this->leftJoin))
                    . ($this->where === null  ?  " " : $this->where)
                    . ($this->order === null  ?  " " : $this->order)
                    . " LIMIT " . $this->limit;

            case 'mssql':
                return 'SELECT ' . "TOP " . $this->limit .  " " . implode(', ', $this->columns)
                    . ' FROM ' . implode(', ', $this->from)
                    . ($this->leftJoin === [] ? '' : $this->strLeftJoin . implode($this->strLeftJoin, $this->leftJoin))
                    . ($this->where === null  ?  " " : $this->where)
                    . ($this->order === null  ?  " " : $this->order);

            default:
                return "NO SQL TYPE";
        }
    }
}
