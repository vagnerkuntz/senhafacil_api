<?php

// used to get mysql database connection
class Database {
 
  // specify your own database credentials
  private $db_host = '127.0.0.1';
  private $db_name = 'senhafacil';
  private $db_user = 'root';
  private $db_pass = '';
  public $conn;

  // get the database connection
  public function getConnection() {
    $this->conn = null;

    try {
      $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
    } catch (PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    }

    return $this->conn;
  }
  
}
