<?php
namespace App\Controller;

use App\Models\User;

class UserController {
    private $userModel;
    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function list() {
        return $this->userModel->getAll();
    }

    public function changeRole($user_id, $role) {
        return $this->userModel->changeRole($user_id, $role);
    }
}