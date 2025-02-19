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

function login(string $email, string $password) {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = getDbConnexion()->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user['password'])) {
        return false;
    }

    $_SESSION['user_id'] = $user['id'];
    header('Location: /profile.php');
    exit;
}

$success = null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $success = login($email, $password);
}



?>

