<?php

namespace Services;

use Helpers\Validator;
use Models\CommentModel;
use Models\ReviewModel;

class CommentService
{
    private CommentModel $comments;
    private ReviewModel $reviews;
    private NotificationService $notifications;

    public function __construct()
    {
        $this->comments = new CommentModel();
        $this->reviews = new ReviewModel();
        $this->notifications = new NotificationService();
    }

    public function add(int $reviewId, int $userId, array $data): array
    {
        $errors = Validator::validate($data, [
            'content' => ['required', 'min:2', 'max:400'],
        ]);

        $review = $this->reviews->findDetailed($reviewId);
        if (!$review) {
            $errors['review'][] = 'Review không tồn tại.';
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $commentId = $this->comments->create([
            'review_id' => $reviewId,
            'user_id' => $userId,
            'content' => trim($data['content']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ((int) $review['user_id'] !== $userId) {
            $this->notifications->create(
                (int) $review['user_id'],
                $userId,
                'comment_review',
                $reviewId,
                'đã bình luận review của bạn: "' . mb_substr($review['title'], 0, 40) . '"'
            );
        }

        return [
            'success' => true,
            'comment' => $this->comments->find($commentId),
        ];
    }

    public function getByReview(int $reviewId): array
    {
        return $this->comments->getByReview($reviewId);
    }
}
