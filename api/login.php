<?php
header("Access-Control-Allow-Origin: http://186.232.179.7/senhafacil_api/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
 
$user->email = $data->email;
$email_exists = $user->emailExists();

include_once 'config/core.php';
include_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
 
if ($email_exists && password_verify($data->password, $user->password)) {

    $token = array(
       "iat" => $issued_at,
       "exp" => $expiration_time,
       "iss" => $issuer,
       "data" => array(
           "id" => $user->id,
           "email" => $user->email,
           "secret_mfa" => $user->secret_mfa
       )
    );
    /*
    $authenticator = new PHPGangsta_GoogleAuthenticator();

    $secret = 'S5TCMSWD7EVMGQTV'; // Isso é usado para gerar o código QR
    $tolerance = 0;

    $checkResult = $authenticator->verifyCode($secret, $data->otp, $tolerance);    

    if ($checkResult) {
        $array['error'] = 'OTP is Validated Succesfully';
    } else {
        $array['error'] = 'Failed';
    }*/

    http_response_code(200);
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "message" => "Successful login.",
            "jwt" => $jwt,
            "otp" => 'otp',
            'error' => ''
        )
    );
} else {
    http_response_code(401);
    echo json_encode(array("error" => "Login failed."));
}
