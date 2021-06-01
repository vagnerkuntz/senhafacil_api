<?php
require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method === 'put') {
  
  parse_str(file_get_contents('php://input'), $input);

  $id = filter_var($input['id']) ?? null;

  if ($id && $title) {
    $sql = $pdo->prepare('where id = :id');
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $sql = $pdo->prepare('where id = :id');
      $sql->bindValue(':id', $id);
      $sql->execute();

    } else {
      $array['error'] = 'ID inexistente';
    }

  } else {
    $array['error'] = 'ID não enviado';
  }

} else {
  $array['error'] = 'Método não permitido (apenas DELETE)';
}

require('../return.php');