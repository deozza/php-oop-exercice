<?php
//session_start();
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function getDbConnexion(): PDO {
    $host = 'php-oop-exercice-db';
    $db = 'blog';
    $user = 'root';
    $password = 'password';

    $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

    return new PDO($dsn, $user, $password);
}

function getUser(): array {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = getDbConnexion()->prepare($sql);
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

function getPosts(): array {
    $sql = "SELECT posts.id, posts.title, posts.created_at
    FROM posts 
    INNER JOIN users ON posts.user_id = users.id
    WHERE posts.user_id = :id
    ORDER BY posts.created_at DESC;
    ";

    $stmt = getDbConnexion()->prepare($sql);
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $posts;
}

function updateProfile(string $name, string $email, string $password) {
    $userId = $_SESSION['user_id'];
    $pdo = getDbConnexion();

    $sql = "UPDATE users SET name = :name, email = :email";
    $params = ['name' => $name, 'email' => $email, 'id' => $userId];

    if($password) {
        $sql .= ", password = :password";
        $params['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header('Location: /profile.php');
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    return updateProfile($name, $email, $password);
}

?>
