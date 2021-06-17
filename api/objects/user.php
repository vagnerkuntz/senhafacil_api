<?php
class User {
    private $conn;
    private $table_name = "users";
  
    public $id;
    public $name;
    public $email;
    public $password;
    public $qrcode;
    public $secret_mfa;
    public $created_at;
    public $update_at;
 
    public function __construct($db) {
        $this->conn = $db;
    }

    // retorna a senha para comparar no login
    function emailExists() {
        $query = "SELECT `id`, `email`, `password`, `secret_mfa`, `name`, `qrcode` FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->password = $row['password'];
            $this->secret_mfa = $row['secret_mfa'];
            $this->name = $row['name'];
            $this->qrcode = $row['qrcode'];
    
            return true;
        }
    
        return false;
    }
 
    function create() {
        if ($this->emailExists()) {
            return false;
        }

        require '../vendor/autoload.php';
        $authenticator = new PHPGangsta_GoogleAuthenticator();
        $this->secret_mfa = $authenticator->createSecret();

        $query = "INSERT INTO " . $this->table_name . " SET name = :name, email = :email, password = :password, secret_mfa = :secret_mfa";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':secret_mfa', $this->secret_mfa);
    
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    
        if ($stmt->execute()) { 
            return true;
        }
    
        return false;
    }
    
    public function update() {
        $password_set = !empty($this->password) ? ", password = :password" : "";
    
        $query = "UPDATE " . $this->table_name . " SET qrcode = :qrcode, firstname = :firstname, lastname = :lastname, email = :email {$password_set} WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);

        // hash the password before saving to database
        if(!empty($this->password)){
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }
    
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    public function updateQrCode() {
        $query = "UPDATE " . $this->table_name . " SET qrcode = :qrcode WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':qrcode', $this->qrcode);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    public function validateToken() {
        
    }
}