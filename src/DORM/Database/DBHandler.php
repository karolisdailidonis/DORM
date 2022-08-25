<?php

namespace DORM\Database;

use DORM\Includes\INIWriter;

class DBHandler extends QueryBuilder
{
    private static ?DBHandler $instance = null;

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): DBHandler
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private $connection = null;

    private $db_name;
    private $db_host;
    private $db_user;
    private $db_password;

    private $setDB;
    private $error;

    // SUPPORT
    public $isMariaDB = false;
    private $isMYSQL = false;


    function __construct()
    {
        $ini = parse_ini_file('config.ini');

        $this->db_type      = $ini['db_type'];
        $this->db_name      = $ini['db_name'];
        $this->db_host      = $ini['db_host'];
        $this->db_user      = $ini['db_user'];
        $this->db_password  = $ini['db_password'];

        $this->setDB        = $ini['dorm_db'];

        $this->connect();
    }

    /**
    * executes a function according to the database type with mysql as default
    */
    public function dbTypeExecute($mysql = null, $mssql = null)
    {
        if (strtolower($this->db_type) == 'mysql' && $mysql) return $mysql();
        if (strtolower($this->db_type) == 'mssql' && $mssql) return $mssql();

        // default function
        return $mysql();
    }

    public function connect(): self
    {
        $this->connection = null;

        try {
            $this->connection = new \PDO(
                $this->dbTypeExecute(
                    mysql: fn () => "mysql:host=$this->db_host; dbname=$this->db_name",
                    mssql: fn () => "sqlsrv:server=$this->db_host; Database=$this->db_name",
                ),
                $this->db_user,
                $this->db_password
            );

            $this->dbTypeExecute(
                mysql: $this->connection->exec("set names utf8"),
            );

            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 1);

            // $dbVersion = $this->connection->query('select version()')->fetchColumn();
            // if (strpos( $dbVersion , "MariaDB" ) ) {
            //     $this->isMariaDB = true;

            //     // defined('DORM_CONSTANTS') or define('DORM_CONSTANTS', array() );

            //     // $val =  DORM_CONSTANTS[0];
            //     // define("IS_MARIADB", true);
            //     // preg_match("/^[0-9\.]+/", $dbVersion, $match);
            //     // define("DB_VERSION", $match[0] );
            // };
        } catch (\PDOException  $exception) {
            $this->error = "No connection to Database: " . $exception->getMessage();
        }
        return $this;
    }

    public function getTables()
    {
        $sql = $this->dbTypeExecute(
            mysql: fn () => 'SHOW TABLES',
            mssql: fn () => "SELECT * FROM SYSOBJECTS WHERE xtype = 'U';",
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
            WHERE REFERENCED_TABLE_SCHEMA = 'kada0005_db4'
        	    AND TABLE_NAME = '{$tableName}'
        ",
            mssql: fn () => "
            SELECT  Tab.TABLE_NAME, Col.TABLE_NAME as REFERENCED_TABLE_NAME, Col.COLUMN_NAME as COLUMN_NAME, Col.COLUMN_NAME as REFERENCED_COLUMN_NAME
            FROM
            INFORMATION_SCHEMA.TABLE_CONSTRAINTS Tab, 
            INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE Col 
                WHERE Tab.CONSTRAINT_CATALOG = 'dud' AND Tab.TABLE_NAME = '{$tableName}'
            ",
        );

        return $this->execute($sql);
    }


    public function isDormDB(): bool
    {
        if ($this->setDB == 'true') return true;

        return false;
    }

    public function setDormDB()
    {

        $exist = $this->execute("SELECT * FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_NAME = 'dorm_model_list' ");

        if (count($exist->fetchAll(\PDO::FETCH_ASSOC)) <= 0) $this->setDatabase();

        $ini = parse_ini_file('config.ini');
        $ini['dorm_db'] = "true";

        $ini = (new INIWriter())->writeValue($ini, __DIR__ . '/config.ini');
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
        $sql = "
        BEGIN TRANSACTION;
        DELETE FROM dorm_model_list WHERE table_name = '{$tableName}';
        INSERT INTO dorm_model_list (table_name, class_name) VALUES ('{$tableName}', '{$className}');
        COMMIT;";

        $this->connection->exec($sql);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function execute(string $sqlQuery)
    {
        $query = $this->connection->prepare($sqlQuery);
        $query->execute();

        return $query;
        // return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
