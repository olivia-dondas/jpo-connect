<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$title = $data['title'] ?? null;
$city = $data['city'] ?? null;
$address = $data['address'] ?? null;
$event_date = $data['event_date'] ?? null;
$capacity = $data['capacity'] ?? null;
if ($id && $title && $city && $address && $event_date && $capacity) {
  $pdo = \App\Core\Database::getConnection();
  $stmt = $pdo->prepare("UPDATE open_days SET title=?, city=?, address=?, event_date=?, capacity=? WHERE id=?");
  $success = $stmt->execute([$title, $city, $address, $event_date, $capacity, $id]);
  echo json_encode(['success' => $success]);
} else {
  echo json_encode(['success' => false, 'error' => 'Champs manquants']);
}