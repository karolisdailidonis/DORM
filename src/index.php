<?php
use DORM\Database\DBHandler;
use DORM\Includes\DORMModel;
use DORM\Includes\TableToModel;
use DORM\Models\Person;
use DORM\Models\TestTable;

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

$columnsPerson = array( 'name', 'surname' ); 

$person = new Person();

$query = $conn
            ->select( $columnsPerson )
            ->from( $person->getTableName() );


$query2 = $conn->select()->from( 'person' );



echo $query;
echo '<br>';
echo $query2;

echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';

print_r($conn->execute($query));

function blubb( DORMModel $a ){
    echo $a->getTableName();
}

$b = new TestTable();

// blubb( $b );


echo '<ul>';
foreach ( $conn->getTables() as $value) {
    // $model = ( new TableToModel( $value, $conn->getColumns($value) ))->writeFile();
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