<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$pdo = \App\Core\Database::getConnection();
$res = $pdo->query("SELECT * FROM open_days");
echo json_encode($res->fetchAll(PDO::FETCH_ASSOC));