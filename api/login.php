<?php
require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method === 'post') {
  $email = filter_input(INPUT_POST, 'email');
  $senha = filter_input(INPUT_POST, 'senha');

  if ($email && $senha) {
    /*
    $sql = $pdo->prepare('')

    $id = $pdo->lastInsertId();
    $array['result'] = [
      'id' => $id
    ];
    */
  } else {
    $array['error'] = 'Campos não enviados';
  }

} else {
  $array['error'] = 'Método não permitido (apenas POST)';
}

require('../return.php');