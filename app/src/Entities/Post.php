<?php

namespace App\Entities;

use App\Lib\Database\DatabaseConnexion;

class Posts {

    private $id;
    private $title;
    private $content;
    private $dbConnexion;


    public function __construct($title, $content) {
        $this->title = $title;
        $this->content = $content;

        $this->dbConnexion = new DatabaseConnexion();
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    function getPosts(): array {
        $currentPage = getPage();
        $postsPerPage = getLimit();
        $offset = ($currentPage - 1) * $postsPerPage;
        
        $sql = "SELECT posts.id, posts.title, posts.created_at
        FROM posts 
        INNER JOIN users ON posts.user_id = users.id
        WHERE posts.user_id = :id
        ORDER BY posts.created_at DESC
        LIMIT 10
        OFFSET $offset;
        ";
    
        $stmt = getDbConnexion()->prepare($sql);
        $stmt->execute(['id' => $_GET['id']]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $posts;
    }

    public function getPostsCount(): int {
        $sql = "SELECT COUNT(*) FROM posts WHERE user_id = :id";
        $stmt = getDbConnexion()->prepare($sql);
        $stmt->execute(['id' => $_GET['id']]);
        $count = $stmt->fetchColumn();
    
        return $count;
    }

    
}