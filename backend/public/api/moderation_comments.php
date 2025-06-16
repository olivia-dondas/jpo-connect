<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
require_once '../../src/Models/Comment.php';
require_once '../../src/Controllers/CommentController.php';

use App\Controller\CommentController;

$pdo = \App\Core\Database::getConnection();
$controller = new CommentController($pdo);

$status = $_GET['status'] ?? 'pending'; // Par défaut, on affiche les "pending"
if (method_exists($controller, 'listAllByStatus')) {
    $comments = $controller->listAllByStatus($status);
    echo json_encode($comments);
} else {
    echo json_encode([]);
}