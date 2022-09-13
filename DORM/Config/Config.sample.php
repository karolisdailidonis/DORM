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

    // In API
    static public $displayErrors = 0;
    
    /*
    * Optional API REQUEST header.
    * Always by default in the API()::Response class method setted: header( 'Content-Type: application/json; charset=UTF-8' ) 
    */
    static public $requestHeadersAPI = [
        'Access-Control-Allow-Origin: *',
        'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
    ];

    // ToDo: Implement multiple token with different rights
    static public $tokens = '';

    // ToDo: Implement
    public static $loglevel = 0;

    // ToDo: Implement
    static public $trusted_domains = [ ];

    // ToDo: Implement array for paths woth DORM as root
    static public $paths = [
        // './' =>  __DIR__ . '../'
    ];

}

?>