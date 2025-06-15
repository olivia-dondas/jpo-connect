<?php

namespace App\Controllers;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}



error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\Core\Database;

class AdminController
{
    public static function dashboard()
    {
        $pdo = Database::getConnection();

        // Récupère toutes les JPO
        $stmt = $pdo->query("SELECT * FROM open_days ORDER BY event_date DESC");
        $jpos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Récupère tous les utilisateurs
        $stmt = $pdo->query("SELECT id, first_name, last_name, email, role FROM users ORDER BY role, last_name");
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "jpos" => $jpos,
            "users" => $users
        ]);
    }
}