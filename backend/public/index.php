<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\JpoController;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/api/jpos' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    JpoController::getAllJpos();
} elseif ($uri === '/api/jpos' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    JpoController::createJpo();
} elseif (preg_match('#^/api/jpos/(\d+)$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    JpoController::getJpoById($matches[1]);
} elseif (preg_match('#^/api/jpos/(\d+)$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    JpoController::updateJpo($matches[1]);
} elseif (preg_match('#^/api/jpos/(\d+)$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    JpoController::deleteJpo($matches[1]);
} elseif ($uri === '/api/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    \App\Controllers\AuthController::login();
} elseif ($uri === '/api/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    \App\Controllers\AuthController::register();
} elseif (preg_match('#^/api/jpos$#', $_SERVER['REQUEST_URI'])) {
    require_once '../src/Controllers/JpoController.php';
    $controller = new JpoController();
    $controller->getByCity($_GET['city'] ?? null);
    exit;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}