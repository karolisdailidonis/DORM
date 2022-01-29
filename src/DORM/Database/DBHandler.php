<?php
namespace DORM\Database;

class DBHandler {

    private $connection = null;

    private $db_name;
    private $db_host;
    private $db_user;
    private $db_password;

    function __construct(){
        $ini = parse_ini_file('config.ini');

        $this->db_name      = $ini['db_name'];
        $this->db_host      = $ini['db_host'];
        $this->db_user      = $ini['db_user'];
        $this->db_password  = $ini['db_password'];
    }

    public function getConnection(){
        $this->connection = null;
        try {
            $this->connection = new \PDO( 
                "mysql:host=$this->db_host; dbname=$this->db_name", 
                $this->db_user, 
                $this->db_password);

            $this->connection->exec("set names utf8");
            
        } catch ( \PDOException  $exception ) {
            echo "No connection to Database: " . $exception->getMessage();
        }
        return $this->connection;
    }

    public function getTables(){
        $sql = 'SHOW TABLES';
        
        $query = $this->connection->query($sql);
        return $query->fetchAll( \PDO::FETCH_COLUMN );
    }

    public function getColumns(){
        $sql = '';

    }

    public function getTableReferences( $tableName ){
        $sql = "
            SELECT  table_name,
                    column_name,
                    referenced_table_name,
                    referenced_column_name 
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = 'kada0005_db4'
			    AND TABLE_NAME = '{$tableName}'
        ";

        return;
    }

}


?>