<?php
namespace App\Controller;

use App\Models\Comment;

class CommentController {
    private $commentModel;

    public function __construct($pdo) {
        $this->commentModel = new Comment($pdo);
    }

    public function list($open_day_id) {
        return $this->commentModel->getAllByJpo($open_day_id);
    }

    public function add($content, $user_id, $open_day_id) {
        return $this->commentModel->add($content, $user_id, $open_day_id);
    }

    public function delete($id, $user_id) {
        return $this->commentModel->delete($id, $user_id);
    }

    public function edit($id, $user_id, $content) {
        return $this->commentModel->edit($id, $user_id, $content);
    }
    public function listAllByStatus($status = 'pending') {
        return $this->commentModel->getAllByStatus($status);
    }
}