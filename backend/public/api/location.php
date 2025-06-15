<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');
require_once '../../src/Models/Location.php';
require_once '../../src/Core/Database.php';

use App\Models\Location;

$city = $_GET['city'] ?? null;

if ($city) {
    $locationModel = new Location();
    $locations = $locationModel->findByCity($city);
    echo json_encode($locations);
    exit;
}

echo json_encode([]);