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
    $model = new TableToModel( $value, $conn->getColumns($value) );
    $model->writeFile();
    echo '<li>'. $value . '</li>';
    
    // echo '<ul>';
    // echo '<li> Columns </li>';
    // foreach ($conn->getColumns( $value ) as $val2) {
    //     echo '<ul>';
    //     echo '<li>' . $val2['COLUMN_NAME'] . ' | ' . $val2['DATA_TYPE'] . ' | ' . $val2['IS_NULLABLE'] . '</li>';
    //     echo '</ul>';
    // }
    // echo '</ul>';
}

echo '</ul>';

// $model = new TableToModel( $value );
// $model->writeFile();

?>