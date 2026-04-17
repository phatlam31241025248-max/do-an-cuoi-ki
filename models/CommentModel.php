<?php

namespace Models;

use Core\BaseModel;

class CommentModel extends BaseModel
{
    protected string $table = 'comments';
    protected array $fillable = ['review_id', 'user_id', 'content', 'created_at', 'updated_at'];

    public function getByReview(int $reviewId): array
    {
        $sql = 'SELECT c.*, u.full_name, u.username, u.avatar
                FROM comments c
                JOIN users u ON u.id = c.user_id
                WHERE c.review_id = :review_id
                ORDER BY c.created_at ASC';
        return $this->fetchAllBySql($sql, ['review_id' => $reviewId]);
    }
}
