<?php
namespace DORM\Database;

use DORM\Database\SQLClasses\Select;
use DORM\Database\SQLClasses\Insert;

class QueryBuilder {

    public static function select( array $columns = null ): Select {
        return new Select( $columns );
    }

    public static function insert( $tableName ): Insert {
        return new Insert( $tableName );
    }

}

?>