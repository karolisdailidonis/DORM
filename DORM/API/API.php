<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;
use DORM\Config\Config;

class API {

    protected $tokenRequiered;
    protected $token = '';

    function __construct( bool $tokenRequiered = false ){
        // $this->tokenRequiered = $tokenRequiered;
        $this->token = Config::$tokens;
        $this->request();
    }

    public function request(){
        $request    = json_decode(file_get_contents("php://input"), true);
        $body       = [];
        $errors     = [];

        if( $this->tokenRequiered && !(isset($request['token']) && $request['token'] == $this->token) ){
            $this->response( [], ['Permission denied']);
            return false;
        }

        if ( isset($request['jobs'] ) && is_array($request['jobs']) ){

            $dbHandler      = DBHandler::getInstance();
            $modelList      = new ModelList( $dbHandler->getConnection());
            $solvedStack    = [];

            foreach ($request['jobs'] as $table) {

                if (isset($table['job'])){
                    $modelFromList = $modelList->findModel($table['from']);

                    if( is_array($modelFromList) && $modelFromList ){

                        // TODO: Make requestJob as class with abstract
                        switch ($table['job']) {
                            case 'read':
                                try {
                                    $modelClass = new $modelFromList['class_name']();
                                    $model      = $modelClass->read( $table );
                                    $stmt       = $dbHandler->execute( $model )->fetchAll(\PDO::FETCH_ASSOC);
                                                                      
                                    $tableData                  = array();
                                    $tableData['rows']          = $stmt;
                                    $tableData['references']    = $modelClass->getReferences( );
                                    $tableData['query']         = $model;

                                    // TODO: before/after clean solution
                                    if( isset($table['after']['toBase64']) ) {

                                        foreach ( $table['after']['toBase64'] as $columnname ) {

                                            foreach ( $tableData['rows'] as $key => $value) {
                                                $tableData['rows'][$key][$columnname] = base64_encode( $tableData['rows'][$key][$columnname] );
                                            }
                                            
                                        }

                                    }

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
                                    // TODO: before/after clean solution
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
                                    $modelClass = new $modelFromList['class_name']();
                                    $model = $modelClass->updateData( $table );
                                    $stmt = $dbHandler->execute( $model );

                                    $tableData                  = array();
                                    $tableData['query']         = $model;

                                    $body[$modelFromList['table_name']] = $tableData;
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
                                $errors[] = array( 'message' => 'wrong job', 'request' => $table );
                                break;
                        }

                    }else {
                        $errors[] = array( 'message' => 'can not found a model in the modellist', 'request' => $table );
                    }
                
                } else {
                    $errors[] = array( 'message' => 'missing key: job', 'request' => $table );
                }
            }

        } else {
            $errors[] = array( 'message' => 'no correct request found');
        }

        $this->response( $body, $errors, $request );
    }

    public function response( $body, $errors, $request){
        header('Content-Type: application/json; charset=UTF-8');

        foreach ( Config::$requestHeadersAPI as $value) {
            header( $value );
        }

        $response = [];
        $response['body'] = $body;
        $response['errors'] = $errors;
        $response['request'] = $request;

        print_r( json_encode( $response ) );
    }

}
?>