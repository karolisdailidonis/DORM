<?php
namespace DORM\Includes;

use DORM\Database\QueryBuilder;

class DORMModel extends QueryBuilder {


    // function to get data
    public function read( array $request ){

        $columns = [];

        foreach ($request['columns'] as $entry) {
            $columns[] = $entry['column'];
        }

        $query =  $this->select( $columns )
                        ->from( $this->tableName );
        
        return strval( $query );
    }

}

?>