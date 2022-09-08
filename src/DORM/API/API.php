<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Config\Config;

class API {

    protected $tokenRequiered;
    protected $token = '';

    function __construct( bool $tokenRequiered = false ){
        $this->tokenRequiered = $tokenRequiered;
        $this->token = Config::$tokens;
        ini_set('display_errors', 0 );
        $this->request();
    }

    public function request(){
        $request    = json_decode(file_get_contents("php://input"), true);
        $body       = [];
        $errors     = [];

        if( $this->tokenRequiered){
            if(  !(isset($request['token']) && $request['token'] == $this->token) ){
                $this->response( [], ['Permission denied']);
                return false;
            } 
        }

        if ( isset($request['tables'] ) && is_array($request['tables']) ){

            $dbHandler      = DBHandler::getInstance();
            $modelList      = new ModelList( $dbHandler->getConnection());
            $solvedStack    = [];

            foreach ($request['tables'] as $table) {

                if (isset($table['requestJob'])){
                    $modelFromList = $modelList->findModel($table['from']);

                    if( is_array($modelFromList) && $modelFromList  != false ){

                        switch ($table['requestJob']) {
                            case 'read':
                                try {
                                    $modelClass = new $modelFromList['class_name']();
                                    $model      = $modelClass->read( $table );
                                    $stmt       = $dbHandler->execute( $model)->fetchAll(\PDO::FETCH_ASSOC);

                                                                      
                                    $tableData                  = array();
                                    $tableData['rows']          = $stmt;
                                    $tableData['references']    = $modelClass->getReferences( );
                                    $tableData['query']         = $model;

                                    $body[$modelFromList['table_name']] = $tableData;
                                    break;

                                } catch (\PDOException $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' =>$table, 'query' => $model );
                                    break;

                                } catch ( \Throwable $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table, 'query' => $model );
                                    break;

                                }
                            case 'insert':
                                try {
                                    if ( isset($table['before']['lastInsertId'] ) ){
                                        $before = $table['before']['lastInsertId'];
                                        $table['values'][ $before['setColumn']] = $solvedStack[ $before['fromTable']]['insertID'];
                                    }

                                    $model          = (new $modelFromList['class_name']())->create( $table );
                                    $stmt           = $dbHandler->execute( $model );
                                    $lastInsertID   = $dbHandler->getConnection()->lastInsertId();
                                    $result         = array( 'insertID' => $lastInsertID );

                                    $solvedStack[ $modelFromList['table_name'] ] = $result;

                                    $tableData              = array();
                                    $tableData['result']    = $result;
                                    $tableData['query']     = $model;

                                    $body[$modelFromList['table_name']] = $tableData;
                                    break;

                                } catch (\PDOException $e) {
                                    $errors[] = array( 
                                        'message' => $e->getMessage() . "( " . $e->getLine() . " | " . $e->getFile() . " )", 
                                        'request' => $table 
                                    );
                                    break;

                                } catch ( \Throwable $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table, 'query' => $model );
                                    break;
                                }
                            case 'update':
                                try {
                                    $model = (new $modelFromList['class_name']())->updateData( $table );
                                    $model = $dbHandler->execute( $model );
                                    break;
                                } catch (\PDOException $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                } catch ( \Throwable $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                }
                            case 'delete':
                                try {
                                    $model = (new $modelFromList['class_name']())->deleteData( $table );
                                    $model = $dbHandler->execute( $model );
                                    break;
                                } catch (\PDOException $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                } catch ( \Throwable $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                }
                            default:          
                                $errors[] = array( 'message' => 'wrong requestJob', 'request' => $table );
                                break;
                        }

                    }else {
                        $errors[] = array( 'message' => 'can not found a model in the modellist', 'request' => $table );
                    }
                
                } else {
                    $errors[] = array( 'message' => 'missing key: requestJob', 'request' => $table );
                }
            }

        } else {
            $errors[] = array( 'message' => 'no correct request found');
        }

        $this->response( $body, $errors );
    }


    public function response( $body, $errors){
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:  POST, GET');
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $response = [];
        // $response['sys'] =  array("db" => (IS_MARIADB) ? "isMariaDB" : "kp", "version" => DB_VERSION);
        $response['body'] = $body;
        $response['errors'] = $errors;

        print_r( json_encode( $response ) );
    }

}
?>