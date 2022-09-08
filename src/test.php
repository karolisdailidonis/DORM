<?php
$data = array(
  'schema'      => 'DORM 0.0.3',
  'token'       => '1234556',
  'tables'      => array(
    array( 
        "requestJob" => "read",
        "from" => "person"
    )
  )
//   'itemKind'    => 0,
//   'value'       => 1,
//   'description' => 'Boa saudaÁ„o.',
//   'itemID'      => '03e76d0a-8bab-11e0-8250-000c29b481aa'
);


// $data = {
//     "schema": "DORM 0.0.3",
//     "token": "1234556",
//     "tables": [
//         {
//             "requestJob": "read",
//             "from": "person",
//             "columns": [
//                 { "column": "surname"},
//                 { "column": "name"}
//             ],
//             "where": {
//                 "column": "person_id",
//                 "value": 80,
//                 "condition": "="
//             }
    
//         }   
//     ]
// }


$options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode( $data ),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);


//code...
$url = 'http://185.233.104.59:8181/DORM/src/api.php';
// $url = '83.246.99.237/test.html';
$context  = stream_context_create( $options );
$result = file_get_contents( $url, false, $context );
$response = json_decode( $result );
print_r( $response );


?>