<?php
namespace DORM\Database;

use DORM\Config\Config;
use DORM\Includes\ErrorHandler;

class DBHandler extends QueryBuilder
{
    private $connection = null;
    private $dbConfig;
    private $error;
    private ?string $dbName = null;

    public function __construct(string $database = 'default', string $tenantDbName = null)
    {
        ErrorHandler::setup();
        if (!isset(Config::$database[$database])) {
            return;
        }
        $this->dbConfig = Config::$database[$database];
        $this->dbName = ($tenantDbName == null) ? $this->dbConfig['dbName'] : $tenantDbName;
        $this->connect();
    }

    /**
     * Executes a function according to the database type with mysql as default
     */
    public function dbTypeExecute($mysql = null, $mssql = null)
    {
        if (strtolower($this->dbConfig['dbtype']) == 'mysql' && $mysql) {
            return $mysql();
        }
        if (strtolower($this->dbConfig['dbtype']) == 'mssql' && $mssql) {
            return $mssql();
        }

        // Default function
        return $mysql();
    }

    public function connect(): self
    {
        $this->connection = null;

        try {

            $this->connection = new \PDO(
                $this->dbTypeExecute(
                    mysql: fn () => "mysql:host=" . $this->dbConfig['dbhost'] . ":" . $this->dbConfig['dbport'] . "; dbname=" . $this->dbName,
                    mssql: fn () => "sqlsrv:server=" . $this->dbConfig['dbhost'] . "," .  $this->dbConfig['dbport'] . "; Database=" . $this->dbName,
                ),
                $this->dbConfig['dbuser'],
                $this->dbConfig['dbpass']
            );

            $this->execute("SET NAMES utf8");

            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 1);
        } catch (\PDOException  $exception) {
            $this->error = "DORM:No connection to Database: " . $exception->getMessage();
        } catch (\Throwable $th) {
            $this->error = "DORM:No connection to Database: " . $th->getMessage();
        }

        return $this;
    }

    public function getTables()
    {
        $sql = $this->dbTypeExecute(
            mysql: fn () => "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='{$this->dbName}' ORDER BY TABLE_NAME",
            mssql: fn () => "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_CATALOG='{$this->dbName}' ORDER BY TABLE_NAME",
        );

        $query = $this->connection->query($sql);
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getColumns($tableName)
    {
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$tableName}'";

        $query = $this->connection->query($sql);
        return $query->fetchAll();
    }

    public function getTableReferences($tableName)
    {
        // TODO: replace REFERENCED_TABLE_SCHEMA
        $sql = $this->dbTypeExecute(
            mysql: fn () =>  "
            SELECT  TABLE_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME 
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = '{$this->dbName}'
        	    AND TABLE_NAME = '{$tableName}'
        ",
            mssql: fn () => "
            SELECT  Tab.TABLE_NAME, Col.TABLE_NAME as REFERENCED_TABLE_NAME, Col.COLUMN_NAME as COLUMN_NAME, Col.COLUMN_NAME as REFERENCED_COLUMN_NAME
            FROM
            INFORMATION_SCHEMA.TABLE_CONSTRAINTS Tab, 
            INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE Col 
                WHERE Tab.CONSTRAINT_CATALOG = '{$this->dbName}' AND Tab.TABLE_NAME = '{$tableName}'
            ",
        );

        return $this->execute($sql);
    }

    public function setDormDB()
    {
        $exist = $this->execute("SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = 'dorm_model_list' ");

        if (count($exist->fetchAll(\PDO::FETCH_ASSOC)) <= 0) {
            $this->setDatabase();
        }
    }

    public function setDatabase()
    {
        $sql = $this->dbTypeExecute(
            mysql: fn () => "
                CREATE TABLE IF NOT EXISTS dorm_model_list (
                    table_name varchar(100) NOT NULL,
                    class_name varchar(100) NOT NULL,
                    create_timestamp timestamp NOT NULL,

                    PRIMARY KEY(table_name)
                );
            ",
            mssql: fn () => "
                    IF  NOT EXISTS (SELECT * FROM sys.objects 
                    WHERE object_id = OBJECT_ID(N'[dbo].[dorm_model_list]') AND type in (N'U'))
                    
                    BEGIN
                    CREATE TABLE dorm_model_list (
                        table_name varchar(100) NOT NULL,
                        class_name varchar(100) NOT NULL,
                        create_timestamp timestamp NOT NULL,
                    
                        PRIMARY KEY(table_name)
                    );
                    END
            ",
        );

        $this->connection->exec($sql);
    }
    public function insertModel(string $tableName, string $className)
    {
        $sql = $this->dbTypeExecute(
            mysql: fn () => "
                REPLACE INTO dorm_model_list ( table_name, class_name)
                VALUES ( '{$tableName}', '{$className}' )
            ",

            mssql: fn () => "
                BEGIN TRANSACTION;
                    DELETE FROM dorm_model_list WHERE table_name = '{$tableName}';
                    INSERT INTO dorm_model_list (table_name, class_name) VALUES ('{$tableName}', '{$className}');
                COMMIT;
            ",
        );

        $this->execute($sql);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getDBType(): string
    {
        return $this->dbConfig['dbtype'];
    }

    public function execute(string $sqlQuery)
    {
        $query = $this->connection->prepare($sqlQuery);
        $query->execute();

        return $query;
    }
}
