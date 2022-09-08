<?php
/*
*
*   DORM configuration class, copy this file an 
*   rename from Config.samle.php to Config.php
*
*/

Namespace DORM\Config;

class Config {

    static public $database = [

        // Set dbtype to => 'mssql' | 'mysql'
        'dbtype' => '',

        'dbhost' => '',
        'dbname' => '',
        'dbuser' => '',
        'dbpass' => '',

        // MariaDB default: 3306 
        'dbport' => '',

    ];

    static public $tokens = '';

}

?>