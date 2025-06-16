<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
require_once '../../src/Models/Comment.php';
require_once '../../src/Controllers/CommentController.php';

use App\Controller\CommentController;

$pdo = \App\Core\Database::getConnection();
$controller = new CommentController($pdo);

$id = $_GET['id'] ?? null;
$user_id = $_GET['user_id'] ?? null;

if ($id && $user_id) {
    $success = $controller->delete($id, $user_id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
}