<?php

namespace DORM\Database\SQLClasses;

use DORM\Database\DBHandler;

class Select
{

    private $columns = [];

    private $from = [];

    private $where = null;

    private $leftJoin = [];

    private $limit = 1000;

    public function __construct(array $columns = null)
    {
        ($columns != null) ? $this->columns = $columns : $this->columns = array('*');
    }

    public function from(string $table, string $alias = null): self
    {
        $this->from[] = $alias === null ? $table : "${table} AS ${alias}";
        return $this;
    }

    // ToDo: Where als eigene Klasse auslagern? wird auch in select gebraucht etc.
    public function where(string $column, string $condition, string $value): self
    {

        $this->where = $column . " " . $condition . " '" . $value . "'";
        return $this;
    }

    // ToDo: reduce to one pair if same ?
    public function join(string $table1, string $table2, string $column1, string $column2 = null): self
    {

        $sql = "%TABLE2% ON %TABLE1%.%COLUMN1% = %TABLE2%.%COLUMN2%";

        $sql = str_replace("%TABLE1%", $table1, $sql);
        $sql = str_replace("%COLUMN1%", $column1, $sql);

        $sql = str_replace("%TABLE2%", $table2, $sql);
        $sql = str_replace("%COLUMN2%", $column1, $sql);

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
        return DBHandler::getInstance()->dbTypeExecute(
            mysql: fn () => 'SELECT ' . implode(', ', $this->columns)
                . ' FROM ' . implode(', ', $this->from)
                . ($this->leftJoin === [] ? '' : ' LEFT JOIN ' . implode(' LEFT JOIN ', $this->leftJoin))
                . ($this->where === null  ?  " " : " WHERE " . $this->where)
                . " LIMIT " . $this->limit,
            mssql: fn () => 'SELECT ' . " TOP " . $this->limit .  " " . implode(', ', $this->columns)
                . ' FROM ' . implode(', ', $this->from)
                . ($this->leftJoin === [] ? '' : ' LEFT JOIN ' . implode(' LEFT JOIN ', $this->leftJoin))
                . ($this->where === null  ?  " " : " WHERE " . $this->where)
        );
    }
}
