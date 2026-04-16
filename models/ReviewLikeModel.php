<?php

namespace Models;

use Core\BaseModel;

class ReviewLikeModel extends BaseModel
{
    protected string $table = 'review_likes';
    protected string $primaryKey = 'review_id';
    protected array $fillable = ['user_id', 'review_id', 'created_at'];

    public function exists(int $userId, int $reviewId): bool
    {
        $row = $this->fetchBySql('SELECT 1 FROM review_likes WHERE user_id = :user_id AND review_id = :review_id LIMIT 1', [
            'user_id' => $userId,
            'review_id' => $reviewId,
        ]);
        return (bool) $row;
    }

    public function toggle(int $userId, int $reviewId): bool
    {
        if ($this->exists($userId, $reviewId)) {
            return $this->executeSql('DELETE FROM review_likes WHERE user_id = :user_id AND review_id = :review_id', [
                'user_id' => $userId,
                'review_id' => $reviewId,
            ]);
        }

        return $this->executeSql('INSERT INTO review_likes (user_id, review_id, created_at) VALUES (:user_id, :review_id, NOW())', [
            'user_id' => $userId,
            'review_id' => $reviewId,
        ]);
    }

    public function countByReview(int $reviewId): int
    {
        $row = $this->fetchBySql('SELECT COUNT(*) AS total FROM review_likes WHERE review_id = :review_id', ['review_id' => $reviewId]);
        return (int) ($row['total'] ?? 0);
    }
}
