<?php

namespace Models;

use Core\BaseModel;

class CollectionModel extends BaseModel
{
    protected string $table = 'collections';
    protected array $fillable = ['user_id', 'name', 'description', 'privacy', 'created_at', 'updated_at'];

    public function getByUser(int $userId): array
    {
        $sql = 'SELECT c.*, COUNT(cp.place_id) AS place_total
                FROM collections c
                LEFT JOIN collection_places cp ON cp.collection_id = c.id
                WHERE c.user_id = :user_id
                GROUP BY c.id
                ORDER BY c.created_at DESC';
        return $this->fetchAllBySql($sql, ['user_id' => $userId]);
    }

    public function featuredPublic(int $limit = 4): array
    {
        $sql = 'SELECT c.*, u.username, COUNT(cp.place_id) AS place_total
                FROM collections c
                JOIN users u ON u.id = c.user_id
                LEFT JOIN collection_places cp ON cp.collection_id = c.id
                WHERE c.privacy = "public"
                GROUP BY c.id
                ORDER BY place_total DESC, c.created_at DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findOwned(int $collectionId, int $userId): ?array
    {
        return $this->fetchBySql('SELECT * FROM collections WHERE id = :id AND user_id = :user_id LIMIT 1', [
            'id' => $collectionId,
            'user_id' => $userId,
        ]);
    }
}
