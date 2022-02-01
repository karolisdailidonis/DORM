<?php
namespace DORM\Database;

use DORM\Database\SQLClasses\Select;

class QueryBuilder {

    public static function select( array $columns = null ): Select {
        return new Select( $columns );
    }

}

?>