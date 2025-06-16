<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
require_once '../../src/Models/User.php';
require_once '../../src/Controllers/UserController.php';

use App\Controller\UserController;

$pdo = \App\Core\Database::getConnection();
$controller = new UserController($pdo);

echo json_encode($controller->list());