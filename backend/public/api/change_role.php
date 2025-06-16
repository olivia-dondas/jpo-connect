<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
require_once '../../src/Models/User.php';
require_once '../../src/Controllers/UserController.php';

use App\Controller\UserController;

$pdo = \App\Core\Database::getConnection();
$controller = new UserController($pdo);

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;
$role = $data['role'] ?? null;

if ($user_id && $role) {
    $success = $controller->changeRole($user_id, $role);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
}