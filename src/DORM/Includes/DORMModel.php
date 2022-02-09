<?php
namespace DORM\Includes;

use DORM\Database\QueryBuilder;

class DORMModel extends QueryBuilder {

    // insert into model query
    public function create( array $request ){

        $columns    = [];
        $values     = [];

        foreach ($request['values'] as $key => $value) {
            $columns[]  = $key;
            $values[]   = $value;
        }

        $query = $this->insert( $this->tableName)
                        ->columns( $columns )
                        ->values( $values );

        return strval( $query );
    }

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


    public function updateData( array $request){
        
        $query = $this->update( $this->tableName );

        foreach ($request['values'] as $key => $value) {
            $query->set( $key, $value );
        }

        if( isset($request['where'] )){
            $query->where( $request['where']['column'], $request['where']['condition'], $request['where']['value'] );
        }

        return strval( $query );
    }

    
    public function delete(){

        $query = "";
    
        return strval( $query );
    }



}

?>