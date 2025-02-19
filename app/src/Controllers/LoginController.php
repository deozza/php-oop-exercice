<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;

class LoginController extends AbstractController {
    private $dbConnexion;

    public function __construct() {
        $this->dbConnexion = new DatabaseConnexion();
    }

    public function process(Request $request): Response {
        if ($request->getMethod() === 'POST') {
            return $this->login($request);
        }

        return $this->render([
            'error' => 'Method not allowed'
        ]);
    }

    public function login(Request $request): Response {
        $email = $request->getPost('email');
        $password = $request->getPost('password');

        if (empty($email) || empty($password)) {
            return $this->render([
                'error' => 'All fields are required'
            ]);
        }

        $this->dbConnexion->query("SELECT * FROM users WHERE email = :email");
        $this->dbConnexion->bind(':email', $email);
        $user = $this->dbConnexion->single();

        if (!$user) {
            return $this->render([
                'error' => 'Invalid email or password'
            ]);
        }

        if (!password_verify($password, $user->password)) {
            return $this->render([
                'error' => 'Invalid email or password'
            ]);
        }

        $_SESSION['user_id'] = $user->id;

        return $this->render([
            'success' => 'Login successful'
        ]);
    }
}