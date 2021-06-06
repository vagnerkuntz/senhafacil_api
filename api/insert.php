<?php
require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method === 'post') {
  $title = filter_input(INPUT_POST, 'title');
  $body = filter_input(INPUT_POST, 'body');

  if ($title && $body) {
    
    $sql = $pdo->prepare('')

    $id = $pdo->lastInsertId();
    $array['result'] = [
      'id' => $id
    ]

  } else {
    $array['error'] = 'Campos não enviados';
  }

} else {
  $array['error'] = 'Método não permitido (apenas POST)';
}

require('../return.php');