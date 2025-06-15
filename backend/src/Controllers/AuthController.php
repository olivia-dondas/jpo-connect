<?php
namespace App\Controllers;

use App\Models\UserManager;

class AuthController
{
    public static function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing credentials']);
            return;
        }
        $user = UserManager::findByEmail($data['email']);
        if ($user && password_verify($data['password'], $user->password_hash)) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    }

    public static function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (
            !$data ||
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        // Vérifie si l'utilisateur existe déjà
        $user = \App\Models\UserManager::findByEmail($data['email']);
        if ($user) {
            http_response_code(409);
            echo json_encode(['error' => 'Email already registered']);
            return;
        }

        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        $result = \App\Models\UserManager::createUser([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password_hash' => $passwordHash,
            'phone_number' => $data['phone_number'] ?? null,
            'role' => $data['role'] ?? 'student'
        ]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Registration failed']);
        }
    }
}
