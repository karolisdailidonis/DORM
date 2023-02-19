<?php
namespace DORM\Database;

use DORM\Database\SQLClasses\Select;
use DORM\Database\SQLClasses\Insert;
use DORM\Database\SQLClasses\Update;
use DORM\Database\SQLClasses\Delete;

class QueryBuilder
{
    public static function select(array $columns = null, string $sqlType): Select
    {
        return new Select($columns, $sqlType);
    }

    public static function insert(string $tableName): Insert
    {
        return new Insert($tableName);
    }

    public static function update(string $tableName, string $sqlType): Update
    {
        return new Update($tableName, $sqlType);
    }

    public static function delete(string $tableName, string $sqlType): Delete
    {
        return new Delete($tableName, $sqlType);
    }
}