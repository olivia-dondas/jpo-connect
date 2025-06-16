<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;
$role = $data['role'] ?? null;
if ($user_id && $role) {
  $pdo = \App\Core\Database::getConnection();
  $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
  $success = $stmt->execute([$role, $user_id]);
  echo json_encode(['success' => $success]);
} else {
  echo json_encode(['success' => false, 'error' => 'Missing data']);
}