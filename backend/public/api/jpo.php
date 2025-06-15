<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require_once '../../src/Models/Jpo.php';
require_once '../../src/Core/Database.php';

use App\Models\Jpo;

$id = $_GET['id'] ?? null;

if ($id) {
    $jpoModel = new Jpo();
    $jpo = $jpoModel->findById($id);
    echo json_encode($jpo);
    exit;
}

echo json_encode([]);