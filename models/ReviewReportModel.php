<?php

namespace Models;

use Core\BaseModel;

class ReviewReportModel extends BaseModel
{
    protected string $table = 'review_reports';
    protected array $fillable = ['review_id', 'user_id', 'reason', 'created_at'];

    public function exists(int $userId, int $reviewId): bool
    {
        $row = $this->fetchBySql('SELECT id FROM review_reports WHERE user_id = :user_id AND review_id = :review_id LIMIT 1', [
            'user_id' => $userId,
            'review_id' => $reviewId,
        ]);
        return (bool) $row;
    }

    public function allDetailed(): array
    {
        $sql = 'SELECT rr.*, u.username AS reporter_username, p.name AS place_name, rv.title AS review_title, rv.status AS review_status
                FROM review_reports rr
                JOIN users u ON u.id = rr.user_id
                JOIN reviews rv ON rv.id = rr.review_id
                JOIN places p ON p.id = rv.place_id
                ORDER BY rr.created_at DESC';
        return $this->fetchAllBySql($sql);
    }
}
