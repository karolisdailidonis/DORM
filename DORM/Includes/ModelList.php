<?php
namespace DORM\Includes;

class ModelList{

    private $modelList = [];
    private $connection = null;

    function __construct( \PDO $connection){
        $this->connection = $connection;
        $this->getList();
    }

    public function getList() {
        $sql = "SELECT * FROM dorm_model_list";
        $query = $this->connection->query($sql);

        $this->modelList = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        return  $this->modelList;
    }

    public function findModel( string $tableName ){
        foreach ($this->modelList as $array) {
           
            if( array_key_exists( 'table_name', $array)){
                if ( $array['table_name'] == $tableName )  return $array;
            }

        }

        return false;
    }

}
?>