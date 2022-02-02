<?php
namespace DORM\API;

class API {

    function __construct(){
        $this->request();
    }


    public function request(){
        $request =  (array) json_decode(file_get_contents("php://input"));
        $body = [];
        $errors = [];


        if ( isset($request['tables'] ) && is_array($request['tables']) ){

            foreach ($request['tables'] as $value) {
                $body[] = 'blubb';
            }
        } else {
            $errors[] =  'no correct request found';
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