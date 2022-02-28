<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;

class API {

    protected $tokkenRequiered;
    protected $tokken = "1234556";

    function __construct( bool $tokkenRequiered = false ){
        $this->tokkenRequiered = $tokkenRequiered;
        ini_set('display_errors', 0);
        $this->request();
    }

    public function request(){
        $request    = json_decode(file_get_contents("php://input"), true);
        $body       = [];
        $errors     = [];

        if( $this->tokkenRequiered){
            if(  !(isset($request['tokken']) && $request['tokken'] == $this->tokken) ){
                $this->response( [], ['Permission denied']);
                return false;
            } 
        }

        if ( isset($request['tables'] ) && is_array($request['tables']) ){

            $dbHandler = new DBHandler();
            $modelList = new ModelList( $dbHandler->getConnection());

            foreach ($request['tables'] as $table) {

                if (isset($table['requestJob'])){
                    $modelFromList = $modelList->findModel($table['from']);

                    if( is_array($modelFromList) && $modelFromList  != false ){

                        switch ($table['requestJob']) {
                            case 'read':
                                try {
                                    $modelClass = new $modelFromList['class_name']();
                                    $model = $modelClass->read( $table );
                                    $stmt = $dbHandler->execute( $model );
                                    $tableData = array();
                                    $tableData['rows'] =  $stmt;
                                    $tableData['references'] = $modelClass->getReferences( );
                                    $tableData['query'] = $model;

                                    $body[$modelFromList['table_name']] = $tableData;
                                    break;
                                } catch (\PDOException $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                } catch ( \Throwable $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                }
                            case 'insert':
                                try {
                                    $model = (new $modelFromList['class_name']())->create( $table );
                                    $model = $dbHandler->execute( $model );
                                    // $body[$modelFromList['table_name']] = json_encode( $model );
                                    break;
                                } catch (\PDOException $e) {
                                    $errors[] = array( 'message' => $e->getMessage(), 'request' => $table );
                                    break;
                                }
                            case 'update':
                                try {
                                    $model = (new $modelFromList['class_name']())->updateData( $table );
                                    $model = $dbHandler->execute( $model );
                                    // $body[$modelFromList['table_name']] = json_encode( $model );
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
                                    // $body[$modelFromList['table_name']] = json_encode( $model );
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
        $response['body'] = $body;
        $response['errors'] = $errors;

        print_r( json_encode( $response ) );
    }

}
?>