<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$data = json_decode(file_get_contents('php://input'), true);
$title = $data['title'] ?? null;
$city = $data['city'] ?? null;
$address = $data['address'] ?? null;
$event_date = $data['event_date'] ?? null;
$capacity = $data['capacity'] ?? null;
if ($title && $city && $address && $event_date && $capacity) {
  $pdo = \App\Core\Database::getConnection();
  $stmt = $pdo->prepare("INSERT INTO open_days (title, city, address, event_date, capacity) VALUES (?, ?, ?, ?, ?)");
  $success = $stmt->execute([$title, $city, $address, $event_date, $capacity]);
  if ($success) {
    $id = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'jpo' => [
      'id' => $id, 'title' => $title, 'city' => $city, 'address' => $address, 'event_date' => $event_date, 'capacity' => $capacity
    ]]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Erreur SQL']);
  }
} else {
  echo json_encode(['success' => false, 'error' => 'Champs manquants']);
}