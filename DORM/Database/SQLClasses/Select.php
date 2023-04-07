<?php

namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;
use DORM\Database\SQLClasses\Where;

class Select
{
    private $columns = null;

    private $group = null;

    private $from = [];

    private $where = null;

    private $leftJoin = null;

    private $limit = 1000;

    private $count = null;

    private $order = null;

    private $sqlType = null;

    private $strLeftJoin = " LEFT JOIN ";

    public function __construct(array $columns = null, string $sqlType)
    {
        // ($columns != null) ? $this->columns = $columns : $this->columns = array('*');
        $this->columns = $columns;

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
    // TODO: implement as trait ?
    public function join(string $table1, string $table2, string $column1, string $column2 = null): self
    {
        $sql = "%TABLE2% ON %TABLE1%.%COLUMN1% = %TABLE2%.%COLUMN2%";

        $sql = str_replace("%TABLE1%", $table1, $sql);
        $sql = str_replace("%COLUMN1%", $column1, $sql);

        $sql = str_replace("%TABLE2%", $table2, $sql);
        $sql = str_replace("%COLUMN2%", $column2, $sql);

        if ( $this->leftJoin == null) {
            $this->leftJoin = [];
        }

        $this->leftJoin[] = $sql;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function count(array $columns): self
    {
        foreach ($columns as $column) {
            if (is_string($column)) {
                $this->columns[] = "COUNT(".$column.") as count_" . str_replace(".", "_", $column);
            } elseif (is_array($column)) {
                $this->columns[] = "COUNT(" . (isset($column['args']) && in_array("distinct", $column['args']) ? " DISTINCT " : "" ) . $column['column'].") as count_" . str_replace(".", "_", $column['column']);
            }
        }

        return $this;
    }

    public function group(array $columns): self
    {
        $this->group = $columns;

        return $this;
    }

    public function __toString(): string
    {
        switch ($this->sqlType) {
            case 'mysql':
                return 'SELECT ' . ($this->columns != null ? implode(', ', $this->columns) : "*")
                    . ' FROM ' . implode(', ', $this->from)
                    . ($this->leftJoin === [] ? '' : $this->strLeftJoin . implode($this->strLeftJoin, $this->leftJoin))
                    . ($this->where === null  ?  " " : $this->where)
                    . ($this->order === null  ?  " " : $this->order)
                    . ($this->group === null  ?  " " : " GROUP BY " . implode(', ', $this->group))
                    . " LIMIT " . $this->limit;

            case 'mssql':
                return 'SELECT ' .  ($this->columns != null ? implode(', ', $this->columns) : "*")
                    . '  FROM ' . implode(', ', $this->from)
                    . ($this->leftJoin === null ? '' : $this->strLeftJoin . implode($this->strLeftJoin, $this->leftJoin))
                    . ($this->where === null  ?  " " : $this->where)
                    . ($this->order === null  ?  " " : $this->order)
                    . ($this->group === null  ?  " " : " GROUP BY " . implode(', ', $this->group));

            default:
                return "NO SQL TYPE";
        }
    }
}
