<?php
namespace DORM\Database\SQLClasses;

class Select {

    private $columns = [];

    private $from = [];

    private $where;
    
    private $leftJoin = [];
    
    public function __construct( array $columns = null ){
        ($columns != null ) ? $this->columns = $columns : $this->columns = array( '*' );
    }

    public function from ( string $table, string $alias = null): self {

        $this->from[] = $alias === null ? $table : "${table} AS ${alias}";
        return $this;
    }

    public function where ( string $table, string $alias = null ): self {
        return $this;
    }

    public function __toString(): string {

        return 'SELECT ' . implode( ', ', $this->columns )
            . ' FROM ' . implode( ', ', $this->from );
    }

}


?>