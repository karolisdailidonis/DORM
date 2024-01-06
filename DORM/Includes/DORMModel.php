<?php
namespace DORM\Includes;

use DORM\Database\QueryBuilder;
use DORM\Database\DBHandler;

// TODO: Move each job to job class
// TODO: Add Methods for check of hidden, policy and other restriction
class DORMModel extends QueryBuilder {

    // insert into model query
    public function create(array $request)
    {
        $columns    = [];
        $values     = [];

        foreach ($request['values'] as $key => $value) {
            $columns[]  = $key;
            $values[]   = $value;
        }

        $query = $this->insert($this->tableName)
                        ->columns($columns)
                        ->values($values);

        return strval($query);
    }

    // function to get data
    public function read(array $request, string $sqlType)
    {
        $columns = [];

         if (isset($request['columns'])) {
             foreach ($request['columns'] as $entry) {
                 $columns[] = $entry['column'];
             }
         }

        $query =  $this->select($columns, $sqlType)
                        ->from($this->tableName);

        // TODO: Buggy
        if (isset($request['embed'])) {
            foreach ($request['embed'] as $embed) {
                $a = $this->getReference($embed['table']);
                $query->join($this->tableName, $embed['table'], $a['column'], $a['referenced_column']);
            }
        }

        if (isset($request['join'])) {
            foreach ($request['join'] as $join) {
                $arr = [];
                $index = 0;
                //TODO: foreach auflösen, unnötig
                foreach ($join as $table => $column) {
                    $arr[ 'table' . $index] = $table;
                    $arr[ 'column' . $index] = $column;
                    $index = $index + 1;
                }
                $query->join($arr['table0'], $arr['table1'], $arr['column0'], $arr['column1']);
            }
        }

        if (isset($request['where']) && is_array($request['where'])) {
            $query->where($request['where']);
        }

        if (isset($request['group']) && is_array($request['group'])) {
            $query->group($request['group']);
        }

        if (isset($request['order'])) {
            $query->order($request['order']);
        }

        if (isset($request['limit'])) {
            $query->limit((int)$request['limit']);
        }

        // TODO: maybe mix with columns array
        if (isset($request['count'])) {
            $query->count($request['count']);
        }
        
        return strval($query);
    }

    public function updateData(array $request, string $sqlType)
    {
        $query = $this->update($this->tableName, $sqlType);

        foreach ($request['values'] as $key => $value) {
            if(is_array($value)) {
                $query->set($key, $value['value'], true);
                continue;
            }
            $query->set($key, $value);
        }

        if (isset($request['where']) && is_array($request['where'])) {
            $query->where($request['where']);
        }

        return strval($query);
    }
    
    public function deleteData(array $request, string $sqlType)
    {
        $query = $this->delete($this->tableName, $sqlType);

        if (isset($request['where']) && is_array($request['where'])) {
            $query->where($request['where']);
        }
    
        return strval($query);
    }

    public function replaceData(array $request, string $sqlType)
    {
        $columns    = [];
        $values     = [];

        foreach ($request['values'] as $key => $value) {
            $columns[]  = $key;
            $values[]   = $value;
        }

        $query = $this->replace($this->tableName, $sqlType)
                        ->columns($columns)
                        ->values($values);

        return strval($query);
    }

    public function getReference(string $referencedTableName)
    {
        return $this->references[$referencedTableName];
    }

    public function getReferences()
    {
        return $this->references;
    }
}