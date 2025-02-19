<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Database\DatabaseConnexion;


class PostController extends AbstractController {

    private $dbConnexion;

    public function __construct() {
        $this->dbConnexion = new DatabaseConnexion();
    }

    public function process(Request $request): Response {
        if($request->getMethod() === 'POST') {
            return $this->createPost($request);
        }
        if($request->getMethod() === 'GET') {
            return $this->getPosts();
        }

        return $this->render([
            'error' => 'Method not allowed'
        ]);}


    public function getPosts(): Response {
        $this->dbConnexion->query("SELECT * FROM posts");
        $result = $this->dbConnexion->resultSet();

        return $this->render([
            'result' => $result
        ]);
    }

    public function createPost(Request $request): Response {
        $title = $request->getPost('title');
        $content = $request->getPost('content');
        $userId = $_SESSION['user_id'];

        if(empty($title) || empty($content)) {
            return $this->render([
                'error' => 'All fields are required'
            ]);
        }

        $this->dbConnexion->query("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
        $this->dbConnexion->bind(':user_id', $userId);
        $this->dbConnexion->bind(':title', $title);
        $this->dbConnexion->bind(':content', $content);
        $this->dbConnexion->execute();

        return $this->render([
            'result' => 'Post created',
            'post' => [
                'title' => $title,
                'content' => $content
            ]
        ]);
    }
}