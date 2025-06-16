<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../../src/Core/Database.php';
$pdo = \App\Core\Database::getConnection();
$sql = "SELECT r.id, u.first_name as user_first_name, u.last_name as user_last_name, od.title as jpo_title
        FROM registrations r
        JOIN users u ON r.user_id = u.id
        JOIN open_days od ON r.open_day_id = od.id";
$res = $pdo->query($sql);
echo json_encode($res->fetchAll(PDO::FETCH_ASSOC));