<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;

class RegisterController extends AbstractController {

    private $dbConnexion;

    public function __construct() {
        $this->dbConnexion = new DatabaseConnexion();

    }

    public function process(Request $request): Response {
        return $this->register($request);
    }

    public function register(Request $request): Response {
        $username = $request->getPost('username');
        $email = $request->getPost('email');
        $password = $request->getPost('password');

        if (empty($username) || empty($email) || empty($password)) {
            return $this->render([
                'error' => 'All fields are required'
            ]);
        }

        $this->dbConnexion->query("SELECT * FROM users WHERE email = :email OR name = :username");
        $this->dbConnexion->bind(':email', $email);
        $this->dbConnexion->bind(':username', $username);
        $user = $this->dbConnexion->single();

        if ($user) {
            return $this->render([
                'error' => 'User already exists'
            ]);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->dbConnexion->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $this->dbConnexion->bind(':name', $username);
        $this->dbConnexion->bind(':email', $email);
        $this->dbConnexion->bind(':password', $hashedPassword);
        $this->dbConnexion->execute();

        return $this->render([
            'success' => 'Info registered successfully for user: ' . $username . ' with email: ' . $email
        ]);
    }
}