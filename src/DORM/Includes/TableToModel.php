<?php
namespace DORM\Includes;

class TableToModel{

    private $filePath;
    private $tableName;
    private $file;
    private $className;
    
    function __construct($tableName){
        $this->tableName = $tableName;
        $this->filePath = dirname( __DIR__ ) . '/Models';
        $this->className = toCamelCase($tableName);
    }

    public function writeFile(){
        $this->file = fopen( $this->filePath . '/' . $this->className . '.php', 'w' );
        $txt = <<<MODEL
        <?php
        namespace DORM\Models;

        class {$this->className} {
            
            private \$tableName = '{$this->tableName}';

            public function getTableName(){
                return \$this->tableName;
            } 

        }

        ?>
        MODEL;

        fwrite( $this->file, $txt);
        fclose( $this->file );
    }

    function toCamelCase($string)
    {
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

}

?>