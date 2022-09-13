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

        if ( isset($request['embed'])) {
            foreach ($request['embed'] as $embed) {
                $a = $this->getReference($embed['table']);
                $query->join($this->tableName, $embed['table'], $a['column'], $a['referenced_column'] );
            }
        }

        if ( isset($request['join'])) {
            foreach ($request['join'] as $join ) {
                $arr = [];
                $index = 0;
                foreach ($join as $table => $column) {
                    $arr[ 'table' . $index] = $table;
                    $arr[ 'column' . $index] = $column;
                    $index = $index + 1;
                }
                $query->join($arr['table0'], $arr['table1'], $arr['column0'], $arr['column1'] );
            }
        }

        if ( isset($request['where']) ) {
            $query->where($request['where']['column'], $request['where']['condition'], $request['where']['value']);
        }

        if (isset($request['limit'])) {
            $query->limit( (int)$request['limit'] );
        }
        
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

    
    public function deleteData( array $request ){

        $query = $this->delete( $this->tableName );

        if ( isset($request['where'])) {
            $query->where($request['where']['column'], $request['where']['condition'], $request['where']['value']);
            }
    
        return strval( $query );
    }

    public function getReference( string $referencedTableName ){
        return $this->references[ $referencedTableName ];
    }

    public function getReferences(){
        return $this->references;
    }

}

?>