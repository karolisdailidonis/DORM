<?php
use DORM\Database\DBHandler;
use DORM\Includes\TableToModel;

include 'DORM/autoload.php';


echo '<h1>All DB Tables: </h1>'; 

function toCamelCase( $string ){
    $string = str_replace( '_', ' ', $string );
    $string = ucwords( $string );
    $string = str_replace( ' ', '', $string );

    return $string;
}


$conn = new DBHandler();
$conn->getConnection();

echo '<ul>';
foreach ( $conn->getTables() as $value) {
    echo '<li>'. $value . '</li>';
}
echo '</ul>';

echo '<h1>All DB Tables als PHP Class Name: </h1>';

echo '<ul>';
foreach ($conn->getTables() as $value) {

    $model = new TableToModel( $value );
    $model->writeFile();
    
    echo '<li>' . toCamelCase( $value ) . '</li>';
}
echo '</ul>';

?>