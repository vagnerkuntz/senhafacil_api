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
 
// se der certo, ele vai retornar os dados do usuário
if ($email_exists && password_verify($data->password, $user->password)) {
    $array = array();

    $authenticator = new PHPGangsta_GoogleAuthenticator();

    if ($user->qrcode == 0) {
        $array['qrcode'] = $authenticator->getQRCodeGoogleUrl('SenhaFacil - Salvar o token', $user->secret_mfa, 'https://vagnerkuntz.com.br');

        // altera o qrcode para salvo
        $user->qrcode = 1;
        if ($user->updateQrCode()) {
            http_response_code(200);
            echo json_encode($array);
            exit;
        } else {
            http_response_code(401);
            echo json_encode(array("error" => "Login falhou ao exibir o QRCODE"));
            exit;
        }
    } else {
        // secret, otp, tolerance
        $checkResult = $authenticator->verifyCode($user->secret_mfa, $data->otp, 0);    

        if ($checkResult) {
            $token = array(
                "iat" => $issued_at->getTimestamp(),
                "iss" => $issuer,
                'nbf' => $issued_at->getTimestamp(),
                "exp" => $expiration_time,
                "data" => array(
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email
                )
            );
             
            http_response_code(200);
            $jwt = JWT::encode($token, $key, 'HS512');

            echo json_encode(array("result" => $jwt));
            exit;
        } else {
            http_response_code(401);
            echo json_encode(array("error" => "Login falhou, autenticador"));
            exit;
        }
    }
} else {
    http_response_code(401);
    echo json_encode(array("error" => "E-mail e/ou senha incorretos"));
    exit;
}
