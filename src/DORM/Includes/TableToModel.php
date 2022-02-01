<?php
namespace DORM\Includes;

class TableToModel{

    private $filePath;
    private $tableName;
    private $file;
    private $className;
    private $columns;
    private $relations;
    
    function __construct( string $tableName, $columns){
        $this->tableName = $tableName;
        $this->filePath = dirname( __DIR__ ) . '/Models';
        $this->className = $this->toCamelCase($tableName);
        $this->columns = $columns;
    }

    public function writeFile(){
        $this->file = fopen( $this->filePath . '/' . $this->className . '.php', 'w' );
        $fileContent = <<<MODEL
        <?php
        namespace DORM\Models;

        class {$this->className} {
            
            private \$tableName = '{$this->tableName}';
        MODEL;

        $fileContent .= <<<MODEL

            private \$columns = array(

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
    }

    function toCamelCase($string){
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

}

?>