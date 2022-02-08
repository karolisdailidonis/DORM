<?php
namespace DORM\API;

use DORM\Database\DBHandler;
use DORM\Includes\ModelList;

class API {

    function __construct(){
        $this->request();
    }

    public function request(){
        $request    = json_decode(file_get_contents("php://input"), true);
        $body       = [];
        $errors     = [];

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
                                    $model = (new $modelFromList['class_name']())->read( $table );
                                    $model = $dbHandler->execute( $model );
                                    $body[$modelFromList['table_name']] = json_encode( $model );
                                    break;
                                } catch (\PDOException $e) {
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
        $response['erros'] = $errors;

        print_r( json_encode( $response ) );
    }

}
?>