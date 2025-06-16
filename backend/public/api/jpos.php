<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');
require_once '../../src/Models/Jpo.php';
require_once '../../src/Core/Database.php';

use App\Models\Jpo;

$locationId = $_GET['location_id'] ?? null;

if ($locationId) {
    $jpoModel = new Jpo();
    $jpos = $jpoModel->findByLocationId($locationId);
    echo json_encode($jpos);
    exit;
}

echo json_encode([]);