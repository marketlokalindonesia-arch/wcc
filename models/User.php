<?php
// models/User.php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $phone;
    public $role;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT id, username, email, password, first_name, last_name, role 
                 FROM " . $this->table_name . " 
                 WHERE email = :email AND status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->role = $row['role'];
                
                return true;
            }
        }
        
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                 SET username=:username, email=:email, password=:password,
                 first_name=:first_name, last_name=:last_name, role=:role";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = $this->generateUsername($this->email);
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":role", $this->role);
        
        return $stmt->execute();
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->phone = $row['phone'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            
            return true;
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                 SET first_name=:first_name, last_name=:last_name, 
                 email=:email, phone=:phone
                 WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    private function generateUsername($email) {
        $username = strtok($email, '@');
        $base_username = $username;
        $counter = 1;
        
        // Check if username exists and generate unique one
        while($this->usernameExists($username)) {
            $username = $base_username . $counter;
            $counter++;
        }
        
        return $username;
    }

    private function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>