<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
require_once '../../src/Models/Comment.php';
require_once '../../src/Controllers/CommentController.php';

use App\Controller\CommentController;

$pdo = \App\Core\Database::getConnection();
$controller = new CommentController($pdo);

$open_day_id = $_GET['open_day_id'] ?? null;
if ($open_day_id) {
    $comments = $controller->list($open_day_id);
    echo json_encode($comments);
} else {
    echo json_encode([]);
}