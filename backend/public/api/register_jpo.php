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
$open_day_id = $data['open_day_id'] ?? null;

if ($user_id && $open_day_id) {
    $pdo = \App\Core\Database::getConnection();

    // Vérifie la capacité
    $cap = $pdo->prepare("SELECT capacity, (
        SELECT COUNT(*) FROM registrations WHERE open_day_id = ?
    ) AS inscrits FROM open_days WHERE id = ?");
    $cap->execute([$open_day_id, $open_day_id]);
    $row = $cap->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['inscrits'] >= $row['capacity']) {
        echo json_encode(['success' => false, 'error' => 'Capacité atteinte']);
        exit;
    }

    // Vérifie si déjà inscrit
    $check = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND open_day_id = ?");
    $check->execute([$user_id, $open_day_id]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Déjà inscrit à cette JPO.']);
        exit;
    }

    // Inscription
    $stmt = $pdo->prepare("INSERT INTO registrations (user_id, open_day_id) VALUES (?, ?)");
    $success = $stmt->execute([$user_id, $open_day_id]);
    if (!$success) {
        echo json_encode(['success' => false, 'error' => $stmt->errorInfo()]);
        exit;
    }
    echo json_encode(['success' => $success]);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Missing data']);