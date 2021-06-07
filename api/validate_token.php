<?php
// required headers
header("Access-Control-Allow-Origin: http://186.232.179.7/senhafacil_api/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// required to decode jwt
include_once 'config/core.php';
include_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;
 
$data = json_decode(file_get_contents("php://input"));
 
$jwt = isset($data->jwt) ? $data->jwt : "";
 
if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        http_response_code(200);
        echo json_encode(array(
            "message" => "Access granted.",
            "data" => $decoded->data
        ));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
}