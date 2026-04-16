<?php

namespace Models;

use Core\BaseModel;

class ReviewModel extends BaseModel
{
    protected string $table = 'reviews';
    protected array $fillable = [
        'user_id', 'place_id', 'rating', 'title', 'content', 'image', 'verified_score', 'rank_score',
        'helpful_count', 'report_count', 'status', 'created_at', 'updated_at'
    ];

    public function getHomeFeed(int $limit = 12): array
    {
        $sql = 'SELECT r.*, u.full_name, u.username, u.avatar, p.name AS place_name, p.slug AS place_slug,
                    p.thumbnail AS place_thumbnail,
                    (SELECT COUNT(*) FROM comments c WHERE c.review_id = r.id) AS comment_count
                FROM reviews r
                JOIN users u ON u.id = r.user_id
                JOIN places p ON p.id = r.place_id
                WHERE r.status = "visible"
                ORDER BY r.rank_score DESC, r.created_at DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPlace(int $placeId): array
    {
        $sql = 'SELECT r.*, u.full_name, u.username, u.avatar,
                    p.name AS place_name, p.slug AS place_slug,
                    (SELECT COUNT(*) FROM comments c WHERE c.review_id = r.id) AS comment_count
                FROM reviews r
                JOIN users u ON u.id = r.user_id
                JOIN places p ON p.id = r.place_id
                WHERE r.place_id = :place_id AND r.status = "visible"
                ORDER BY r.created_at DESC';
        return $this->fetchAllBySql($sql, ['place_id' => $placeId]);
    }

    public function getByUser(int $userId): array
    {
        $sql = 'SELECT r.*, u.full_name, u.username, u.avatar,
                    p.name AS place_name, p.slug AS place_slug,
                    (SELECT COUNT(*) FROM comments c WHERE c.review_id = r.id) AS comment_count
                FROM reviews r
                JOIN users u ON u.id = r.user_id
                JOIN places p ON p.id = r.place_id
                WHERE r.user_id = :user_id
                ORDER BY r.created_at DESC';
        return $this->fetchAllBySql($sql, ['user_id' => $userId]);
    }

    public function getFollowingFeed(int $userId): array
    {
        $sql = 'SELECT r.*, u.full_name, u.username, u.avatar, p.name AS place_name, p.slug AS place_slug,
                    (SELECT COUNT(*) FROM comments c WHERE c.review_id = r.id) AS comment_count
                FROM reviews r
                JOIN users u ON u.id = r.user_id
                JOIN places p ON p.id = r.place_id
                WHERE r.status = "visible" AND r.user_id IN (
                    SELECT following_id FROM user_follows WHERE follower_id = :user_id
                )
                ORDER BY r.created_at DESC
                LIMIT 30';
        return $this->fetchAllBySql($sql, ['user_id' => $userId]);
    }

    public function findDetailed(int $reviewId): ?array
    {
        $sql = 'SELECT r.*, u.full_name, u.username, u.avatar, p.name AS place_name, p.slug AS place_slug
                FROM reviews r
                JOIN users u ON u.id = r.user_id
                JOIN places p ON p.id = r.place_id
                WHERE r.id = :review_id LIMIT 1';
        return $this->fetchBySql($sql, ['review_id' => $reviewId]);
    }

    public function updateRankScore(int $reviewId): bool
    {
        $sql = 'UPDATE reviews r
                SET rank_score = (r.rating * 1.5) + (r.helpful_count * 1.2) + (r.verified_score * 0.8) - (r.report_count * 1.5)
                WHERE r.id = :review_id';
        return $this->executeSql($sql, ['review_id' => $reviewId]);
    }

    public function forAdmin(): array
    {
        $sql = 'SELECT r.*, u.username, p.name AS place_name
                FROM reviews r
                JOIN users u ON u.id = r.user_id
                JOIN places p ON p.id = r.place_id
                ORDER BY r.created_at DESC';
        return $this->fetchAllBySql($sql);
    }
}
