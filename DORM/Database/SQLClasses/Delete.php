<?php
namespace DORM\Database\SQLClasses;

class Delete {

    private $table;

    private $where = null;


    public function __construct( string $table ){
        $this->table = $table;
    }

    // TODO: Where als eigene Klasse auslagern? wird auch in select gebraucht etc.
    public function where(string $column, string $condition, string $value): self
    {
        $this->where = $column . " " . $condition . " " . $value;
        return $this;
    }

    public function __toString(){

        return "DELETE FROM " . $this->table
                . ($this->where === null  ?  " " : " WHERE " . $this->where);
    }

}
