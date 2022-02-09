<?php
namespace DORM\Database;

use DORM\Database\SQLClasses\Select;
use DORM\Database\SQLClasses\Insert;
use DORM\Database\SQLClasses\Update;

class QueryBuilder {

    public static function select( array $columns = null ): Select {
        return new Select( $columns );
    }

    public static function insert( string $tableName ): Insert {
        return new Insert( $tableName );
    }

    public static function update( string $tableName ): Update{
        return new Update( $tableName );
    }

}

?>