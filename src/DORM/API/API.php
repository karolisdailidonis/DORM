<?php
namespace DORM\API;

class API {

    function __construct(){
        $this->testResponse();
    }

    public function response(){

    }

    public function testResponse(){
        header('Content-Type: application/json; charset=UTF-8');
        echo '{ "tables": [ { "blubb": "blabb"} ] }';
    }

}
?>