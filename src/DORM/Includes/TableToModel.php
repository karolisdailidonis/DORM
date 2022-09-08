<?php
namespace DORM\Includes;

class TableToModel{

    private $filePath;
    private $tableName;
    private $file;
    private $className;
    private $columns;
    private $relations;
    private $references;
    
    function __construct( string $tableName, $columns, $references = null ){
        $this->tableName = $tableName;
        $this->filePath = dirname( __DIR__ ) . '/Models';
        $this->className = $this->toCamelCase($tableName);
        $this->columns = $columns;
        $this->references = $references;
    }

    /*
    *   Check if folder for Models exist and is writeable
    */
    public static function writeAccess() : bool {
        // ToDo: clean side effects after refactor logging

        $path = dirname( __DIR__ ) . '/Models';
        
        if ( !file_exists($path)) {
            echo "DORM: Missing Folder<br>";
            echo $path . '<br>';
            return false;
        }
        
        if ( !is_writable( $path )) {
            echo "DORM: Is nor writeable <br>";
            echo $path . '<br>';
            return false;
        }

        return true;
    }

    public function writeFile(){
        $this->file = fopen( $this->filePath . '/' . $this->className . '.php', 'w' );
        $fileContent = <<<MODEL
        <?php
        /*
        * DORM generated class
        */

        use DORM\Includes\DORMModel;

        class {$this->className} extends DORMModel {
            
            protected \$tableName = '{$this->tableName}';
        MODEL;

        $fileContent .= <<<MODEL

            protected \$columns = array(

        MODEL;

        foreach ( $this->columns as $column ) {
            $fileContentCol = <<<MODEL
                    '%COLUMN_NAME%' => array( 'type' => '%DATA_TYPE%', 'length' => %CHARACTER_MAXIMUM_LENGTH%, 'nullable' => '%IS_NULLABLE%'),
            
            MODEL;

            $fileContentCol = str_replace("%COLUMN_NAME%", $column['COLUMN_NAME'], $fileContentCol );
            $fileContentCol = str_replace("%DATA_TYPE%", $column['DATA_TYPE'], $fileContentCol );
            $fileContentCol = str_replace(
                "%CHARACTER_MAXIMUM_LENGTH%", 
                ($column['CHARACTER_MAXIMUM_LENGTH']) ? $column['CHARACTER_MAXIMUM_LENGTH'] : -1, 
                $fileContentCol );
            $fileContentCol = str_replace("%IS_NULLABLE%", $column['IS_NULLABLE'], $fileContentCol );
            $fileContent .= $fileContentCol;
        }

        $fileContent .= <<<MODEL

            );

            protected \$references = array(

        MODEL;
        
        if( $this->references != null ){
            
            foreach ( $this->references as $reference ) {
                $fileContentCol = <<<MODEL
                        '%REFERENCED_TABLE_NAME%' => array( 'column' => '%COLUMN_NAME%', 'referenced_column' => '%REFERENCED_COLUMN_NAME%' ),
                
                MODEL;
    
                $fileContentCol = str_replace("%REFERENCED_TABLE_NAME%", $reference['REFERENCED_TABLE_NAME'], $fileContentCol );
                $fileContentCol = str_replace("%COLUMN_NAME%", $reference['COLUMN_NAME'], $fileContentCol );
                $fileContentCol = str_replace("%REFERENCED_COLUMN_NAME%", $reference['REFERENCED_COLUMN_NAME'], $fileContentCol );
                $fileContent .= $fileContentCol;
            }

        }

        $fileContent .= <<<MODEL

            );

        MODEL;

        $fileContent .= <<<MODEL

            public function getTableName(){
                return \$this->tableName;
            } 

        }

        ?>
        MODEL;

        fwrite( $this->file, $fileContent);
        fclose( $this->file );

        return array( 'tableName' => $this->tableName, "className" => $this->className );
    }

    function toCamelCase($string){
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

}

?>