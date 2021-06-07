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

// set product property values
$user->name = $data->name;
$user->email = $data->email;
$user->password = $data->password;

if (empty($user->name) || empty($user->email) || empty($user->password)) {
    http_response_code(400);
    echo json_encode(array("message" => "Preencha todos os campos"));
    exit;
}

if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    if ($data->password_confirm === $data->password) {
        if (!empty($user->name) && !empty($user->email) && !empty($user->password)) {
            if ($user->create()) {
                http_response_code(200);
                echo json_encode(array("message" => "Usuário criado com sucesso"));
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Este e-mail já foi utilizado"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Não foi possível criar seu usuário"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Confirmação de senha incorreta"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Você precisa digitar um e-mail válido"));
}
