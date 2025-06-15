<?php

namespace App\Models;

use App\Core\Database;

class Location
{
    public function findByCity($city)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE city = ?");
        $stmt->execute([$city]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}