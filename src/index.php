<?php
use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Includes\Setup;

include 'DORM/autoload.php';

new Setup();

$conn = new DBHandler();

$modelList = new ModelList( $conn->getConnection() );

?>