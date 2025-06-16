<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
require_once '../../src/Models/Comment.php';
require_once '../../src/Controllers/CommentController.php';

use App\Controller\CommentController;

$pdo = \App\Core\Database::getConnection();
$controller = new CommentController($pdo);

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;
$open_day_id = $data['open_day_id'] ?? null;
$content = $data['content'] ?? null;

if ($user_id && $open_day_id && $content) {
    $success = $controller->add($content, $user_id, $open_day_id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Champs manquants']);
}