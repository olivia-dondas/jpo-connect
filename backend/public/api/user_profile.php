<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '/Applications/MAMP/htdocs/jpo-connect/backend/src/Core/Database.php';
use App\Core\Database;

$pdo = Database::getConnection();

$email = $_GET['email'] ?? '';
if (!$email) {
    echo json_encode(["success" => false, "message" => "Email manquant"]);
    exit;
}

// Récupère les infos utilisateur
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone_number, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "message" => "Utilisateur non trouvé"]);
    exit;
}

// Récupère les JPO auxquelles il est inscrit
$stmt = $pdo->prepare("
    SELECT j.id, j.title, j.event_date
    FROM registrations r
    JOIN open_days j ON r.open_day_id = j.id
    WHERE r.user_id = ?
");
$stmt->execute([$user['id']]);
$jpos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "user" => $user,
    "jpos" => $jpos
]);