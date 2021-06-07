<?php
class User {
    private $conn;
    private $table_name = "users";
  
    public $id;
    public $name;
    public $email;
    public $password;
    public $created_at;
    public $update_at;
 
    public function __construct($db) {
        $this->conn = $db;
    }
 
    function create() {
        if ($this->emailExists()) {
            return false;
        }

        require '../vendor/autoload.php';

        $authenticator = new PHPGangsta_GoogleAuthenticator();
        $secret = $authenticator->createSecret();

        $query = "INSERT INTO " . $this->table_name . " SET name = :name, email = :email, password = :password, secret_mfa = :secret_mfa";
        $stmt = $this->conn->prepare($query);
    
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->secret_mfa = $secret;
    
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
    
    function emailExists() { // retorna a senha para comparar no login
        $query = "SELECT `id`, `password`, `secret_mfa` FROM " . $this->table_name . " WHERE email = ? LIMIT 0, 1";
        $stmt = $this->conn->prepare( $query );
    
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->password = $row['password'];
            $this->secret_mfa = $row['secret_mfa'];
    
            return true;
        }
    
        return false;
    }
 
    public function update() {
        $password_set = !empty($this->password) ? ", password = :password" : "";
    
        $query = "UPDATE " . $this->table_name . " SET firstname = :firstname, lastname = :lastname, email = :email {$password_set} WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
    
        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
    
        // hash the password before saving to database
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
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
}