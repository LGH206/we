<?php
header('Content-Type: application/json; charset=utf-8');

$pdo = new PDO('mysql:host=localhost;dbname=suckhoe;charset=utf8mb4', 'root', '');

$q = $_GET['q'] ?? '';

if ($q) {
    $stmt = $pdo->prepare("SELECT title, path FROM posts WHERE title LIKE :q LIMIT 10");
    $stmt->execute(['q' => "$q%"]); 
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([]);
}
?>