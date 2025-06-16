<?php
namespace App\Models;

use PDO;

class User {
    public int $id;
    public ?string $first_name;
    public ?string $last_name;
    public string $email;
    public string $password_hash;
    public ?string $phone_number;
    public string $role;
    public ?string $google_id;
    public ?string $linkedin_id;
    public string $created_at;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT id, first_name, last_name, email, role FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeRole($user_id, $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $user_id]);
    }
}
