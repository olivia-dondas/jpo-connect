<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserManager
{
    public static function findByEmail(string $email): ?User
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchObject(User::class);
        return $user ?: null;
    }
    public static function createUser(array $data): bool
    {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password_hash, phone_number, role) VALUES (:first_name, :last_name, :email, :password_hash, :phone_number, :role)");
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':phone_number' => $data['phone_number'],
            ':role' => $data['role']
        ]);
    }
}