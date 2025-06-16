<?php
namespace App\Models;

use PDO;

class Comment {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllByJpo($open_day_id) {
        $stmt = $this->pdo->prepare("SELECT c.id, c.content, c.user_id, c.created_at, c.moderation_status, u.first_name, u.last_name
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.open_day_id = ?
            ORDER BY c.created_at DESC");
        $stmt->execute([$open_day_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($content, $user_id, $open_day_id) {
        $stmt = $this->pdo->prepare("INSERT INTO comments (content, user_id, open_day_id, moderation_status) VALUES (?, ?, ?, 'pending')");
        return $stmt->execute([$content, $user_id, $open_day_id]);
    }

    public function delete($id, $user_id) {
        $stmt = $this->pdo->prepare("SELECT user_id FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $comment = $stmt->fetch();
        if ($comment && $comment['user_id'] == $user_id) {
            $del = $this->pdo->prepare("DELETE FROM comments WHERE id = ?");
            return $del->execute([$id]);
        }
        return false;
    }

    public function edit($id, $user_id, $content) {
        $stmt = $this->pdo->prepare("SELECT user_id FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $comment = $stmt->fetch();
        if ($comment && $comment['user_id'] == $user_id) {
            $upd = $this->pdo->prepare("UPDATE comments SET content = ?, moderation_status = 'pending' WHERE id = ?");
            return $upd->execute([$content, $id]);
        }
        return false;
    }

    public function getAllByStatus($status = 'pending') {
        $stmt = $this->pdo->prepare("SELECT c.id, c.content, c.user_id, c.open_day_id, c.created_at, c.moderation_status, u.first_name, u.last_name, od.title
            FROM comments c
            JOIN users u ON c.user_id = u.id
            JOIN open_days od ON c.open_day_id = od.id
            WHERE c.moderation_status = ?
            ORDER BY c.created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}