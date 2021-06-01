<?php
require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method === 'put') {
  
  parse_str(file_get_contents('php://input'), $input);

  $id = filter_var($input['id']) ?? null;
  $title = filter_var($input['title']) ?? null;

  if ($id && $title) {
    $sql = $pdo->prepare('where id = :id');
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {

    } else {
      $array['error'] = 'ID inexistente';
    }

  } else {
    $array['error'] = 'Dados não enviados';
  }

} else {
  $array['error'] = 'Método não permitido (apenas PUT)';
}

require('../return.php');