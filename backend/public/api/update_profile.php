
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
$first_name = $data["first_name"] ?? "";
$last_name = $data["last_name"] ?? "";
$phone_number = $data["phone_number"] ?? "";

if (!$email) {
    echo json_encode(["success" => false, "message" => "Email manquant"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone_number = ? WHERE email = ?");
if ($stmt->execute([$first_name, $last_name, $phone_number, $email])) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
}