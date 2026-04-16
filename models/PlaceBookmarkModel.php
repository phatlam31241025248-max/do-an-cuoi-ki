<?php

namespace Models;

use Core\BaseModel;

class PlaceBookmarkModel extends BaseModel
{
    protected string $table = 'place_bookmarks';
    protected string $primaryKey = 'place_id';
    protected array $fillable = ['user_id', 'place_id', 'created_at'];

    public function exists(int $userId, int $placeId): bool
    {
        return (bool) $this->fetchBySql('SELECT 1 FROM place_bookmarks WHERE user_id = :user_id AND place_id = :place_id LIMIT 1', [
            'user_id' => $userId,
            'place_id' => $placeId,
        ]);
    }

    public function toggle(int $userId, int $placeId): bool
    {
        if ($this->exists($userId, $placeId)) {
            return $this->executeSql('DELETE FROM place_bookmarks WHERE user_id = :user_id AND place_id = :place_id', [
                'user_id' => $userId,
                'place_id' => $placeId,
            ]);
        }

        return $this->executeSql('INSERT INTO place_bookmarks (user_id, place_id, created_at) VALUES (:user_id, :place_id, NOW())', [
            'user_id' => $userId,
            'place_id' => $placeId,
        ]);
    }

    public function getByUser(int $userId): array
    {
        $sql = 'SELECT p.*, c.name AS category_name, pb.created_at AS bookmarked_at
                FROM place_bookmarks pb
                JOIN places p ON p.id = pb.place_id
                JOIN categories c ON c.id = p.category_id
                WHERE pb.user_id = :user_id
                ORDER BY pb.created_at DESC';
        return $this->fetchAllBySql($sql, ['user_id' => $userId]);
    }
}
