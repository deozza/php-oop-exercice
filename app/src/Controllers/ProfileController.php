<?php 

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;

class ProfileController extends AbstractController
{
    private $dbConnexion;

    public function __construct() {
        $this->dbConnexion = new DatabaseConnexion();
    }

    public function process(Request $request): Response {
        if ($request->getMethod() === 'POST') {
            return $this->updateProfile($request);
        }

        return $this->render([
            'error' => 'Method not allowed'
        ]);
    }

    public function updateProfile(Request $request): Response {
        $userId = $_SESSION['user_id'];
        $name = $request->getPost('name');
        $email = $request->getPost('email');
        $password = $request->getPost('password');

        if (empty($name) || empty($email)) {
            return $this->render([
                'error' => 'Name and email are required'
            ]);
        }

        $sql = "UPDATE users SET name = :name, email = :email";
        $params = ['name' => $name, 'email' => $email, 'id' => $userId];

        if (!empty($password)) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->getDbConnexion()->prepare($sql);
        $stmt->execute($params);

        return $this->render([
            'success' => 'Profile updated successfully'
        ]);
    }

    private function getDbConnexion() {
        return $this->dbConnexion->getPdo();
    }
}