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

        'default' => [

            // Set dbtype to => 'mssql' | 'mysql'
            'dbtype' => '',
    
            // default: localhost
            'dbhost' => '',
    
            'dbname' => '',
    
            'dbuser' => '',
    
            'dbpass' => '',
    
            // default MariaDB: 3306 
            'dbport' => '',
        ]

    ];

    /*
    * Optional API REQUEST header.
    * Always by default in the API()::Response class method setted: header( 'Content-Type: application/json; charset=UTF-8' ) 
    */
    static public $requestHeadersAPI = [
        'Access-Control-Allow-Origin: *',
        'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
    ];

    // Only for SimpleToken Authentification
    static public $token = '';

    /*
    * Default is false, has little effect on setup and api, all errors and exceptios are caught as far as possible
    */
    static public $displayErrors = false;
    
    /*
    * Default is false, if true, then it is important that a log path exists and write permissions are available 
    */
    static public $logErrors = false;
    
    /*
    * Path to the log incl. file name, example command for write permission: chown -R www-data Logs
    */
    static public $paths = [
        'logs' => __DIR__ . '/../Logs/errors.log'
    ];
    
    // TODO: Implement
    public static $loglevel = 0;

    // TODO: Implement
    static public $trusted_domains = [];
}

?>