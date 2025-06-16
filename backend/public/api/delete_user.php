<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$id = $_GET['id'] ?? null;
if ($id) {
  $pdo = \App\Core\Database::getConnection();
  $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
  $success = $stmt->execute([$id]);
  echo json_encode(['success' => $success]);
} else {
  echo json_encode(['success' => false, 'error' => 'Missing id']);
}