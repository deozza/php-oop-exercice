<?php
//session_start();
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}



function getPage(): int {
    return $_GET['page'] ?? 1;
}

function getLimit(): int {
    return $_GET['limit'] ?? 10;
}


function getPostsCount(): int {
    $sql = "SELECT COUNT(*) FROM posts WHERE user_id = :id";
    $stmt = getDbConnexion()->prepare($sql);
    $stmt->execute(['id' => $_GET['id']]);
    $count = $stmt->fetchColumn();

    return $count;
}

function getPagination(): array {
    $postsCount = getPostsCount();
    $postsPerPage = getLimit();
    $pagesCount = ceil($postsCount / $postsPerPage);

    return [
        'pagesCount' => $pagesCount,
        'currentPage' => getPage(),
    ];
}


?>

