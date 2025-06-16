<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$pdo = \App\Core\Database::getConnection();
$jpo_id = $_GET['jpo_id'] ?? null;
if ($jpo_id) {
  $stmt = $pdo->prepare("SELECT u.first_name, u.last_name, u.email
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    WHERE r.open_day_id = ?");
  $stmt->execute([$jpo_id]);
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
  echo json_encode([]);
}