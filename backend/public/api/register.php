<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
header('Content-Type: application/json');
require_once '/Applications/MAMP/htdocs/jpo-connect/backend/src/Core/Database.php';


use App\Core\Database;

$pdo = Database::getConnection();
if (!$pdo) {
    die("Erreur connexion BDD");
}

$data = json_decode(file_get_contents("php://input"), true);
$first_name = $data['first_name'] ?? null;
$last_name = $data['last_name'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if ($first_name && $last_name && $email && $password) {
    // Vérifie si l'email existe déjà
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Email déjà utilisé']);
        exit;
    }
    // Hash du mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$first_name, $last_name, $email, $password_hash]);
    echo json_encode(['success' => $success]);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Champs manquants']);
