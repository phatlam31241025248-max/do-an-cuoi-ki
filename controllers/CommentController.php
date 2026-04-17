<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\CommentService;

class CommentController extends BaseController
{
    public function store(string $id): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $service = new CommentService();
        $result = $service->add((int) $id, (int) current_user()['id'], $this->request->all());

        if (!$result['success']) {
            $this->jsonError('Không thể thêm bình luận.', ['errors' => $result['errors']]);
        }

        $comments = $service->getByReview((int) $id);
        $latest = end($comments);

        $this->jsonSuccess('Bình luận thành công.', [
            'comment' => $latest,
            'comment_count' => count($comments),
        ]);
    }
}
