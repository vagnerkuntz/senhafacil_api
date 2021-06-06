<?php
$db_host = '127.0.0.1';
$db_name = 'senhafacil';
$db_user = 'root';
$db_pass = '';

try {
  $pdo = new PDO("mysql:dbname=".$db_name.";host=".$db_host, $db_user, $db_pass);
  
  $array = [
    'error' => '',
    'result' => []
  ];
  
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  exit;
}
