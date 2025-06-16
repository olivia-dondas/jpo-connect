<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '/Applications/MAMP/htdocs/jpo-connect/backend/src/Core/Database.php';

use App\Core\Database;

$pdo = Database::getConnection();

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"] ?? "";
$password = $data["password"] ?? "";

$stmt = $pdo->prepare("SELECT id, email, password_hash, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password_hash"])) {
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"]
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Identifiants invalides"
    ]);
}
