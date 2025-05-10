<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Entities\User;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;



class UserController extends AbstractController {

    private $dbConnexion;
  
    public function __construct()
    {
        $this->dbConnexion = new DatabaseConnexion();
    }

    public function process(Request $request): Response {
        return $this->getUsers($request);
    }

    public function getUsers(Request $request): Response {
        $this->dbConnexion->query("SELECT * FROM users");
        $result = $this->dbConnexion->resultSet();

        return $this->render([
            'result' => $result
        ]);
    }

    
        
}