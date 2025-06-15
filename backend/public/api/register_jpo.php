<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
header('Content-Type: application/json');
require_once '../../src/Core/Database.php';

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;
$jpo_id = $data['jpo_id'] ?? null;

if ($user_id && $jpo_id) {
    $pdo = \App\Core\Database::getConnection();
    $stmt = $pdo->prepare("INSERT INTO jpo_registrations (user_id, jpo_id) VALUES (?, ?)");
    $success = $stmt->execute([$user_id, $jpo_id]);
    echo json_encode(['success' => $success]);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Missing data']);