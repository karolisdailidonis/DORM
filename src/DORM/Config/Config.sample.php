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

        // default: localhost
        'dbhost' => '',

        'dbname' => '',

        'dbuser' => '',

        'dbpass' => '',

        // default MariaDB: 3306 
        'dbport' => '',

    ];

    // ToDo: Implement multiple token with different rights
    static public $tokenRequiered = true;
    static public $tokens = '';

    // ToDo: Implement
    public static $loglevel = 0;

    // ToDo: Implement
    static public $trusted_domains = [ ];

}

?>