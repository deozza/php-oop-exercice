<?php

namespace App\Entities;

use App\Lib\Database\DatabaseConnexion;

class User {
    private $id;
    private $name;
    private $email;
    private $password;
    private $dbConnexion;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;

        $this->dbConnexion = new DatabaseConnexion();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getUsers(Request $request): Response {
        $this->dbConnexion->query("SELECT * FROM users");
        $result = $this->dbConnexion->resultSet();

        return $this->render([
            'result' => $result
        ]);
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    function register(string $username, string $email, string $password) {
        $sql = "SELECT * FROM users WHERE email = :email OR name = :username";
        $stmt = getDbConnexion()->prepare($sql);
        $stmt->execute(['email' => $email, 'username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!empty($user)) {
            return false;
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password);";
        $stmt = getDbConnexion()->prepare($sql);
        $stmt->execute(['name' => $username, 'email' => $email, 'password' => $hashedPassword]);
        
        header('Location: /login.php');
        exit;
    }

}