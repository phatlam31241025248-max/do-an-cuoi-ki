<?php

namespace Models;

use Core\BaseModel;

class PlaceModel extends BaseModel
{
    protected string $table = 'places';
    protected array $fillable = [
        'category_id', 'name', 'slug', 'address', 'description', 'thumbnail', 'cover_image', 'phone',
        'open_hours', 'price_range', 'avg_rating', 'review_count', 'created_by', 'created_at', 'updated_at'
    ];

    public function search(array $filters, int $page = 1, int $perPage = 9): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['keyword'])) {
            $conditions[] = '(p.name LIKE :keyword OR p.address LIKE :keyword OR c.name LIKE :keyword)';
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['category'])) {
            $conditions[] = 'c.slug = :category';
            $params['category'] = $filters['category'];
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sort = match ($filters['sort'] ?? 'latest') {
            'rating' => 'p.avg_rating DESC, p.review_count DESC',
            'popular' => 'p.review_count DESC, p.avg_rating DESC',
            default => 'p.created_at DESC',
        };

        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM places p
                JOIN categories c ON c.id = p.category_id
                ' . $where . '
                ORDER BY ' . $sort . '
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) AS total FROM places p JOIN categories c ON c.id = p.category_id ' . $where;
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue(':' . $key, $value);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetch()['total'];

        return ['items' => $items, 'total' => $total];
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.full_name AS creator_name
                FROM places p
                JOIN categories c ON c.id = p.category_id
                LEFT JOIN users u ON u.id = p.created_by
                WHERE p.slug = :slug LIMIT 1';
        return $this->fetchBySql($sql, ['slug' => $slug]);
    }

    public function getHotPlaces(int $limit = 5): array
    {
        $sql = 'SELECT p.*, c.name AS category_name FROM places p JOIN categories c ON c.id = p.category_id ORDER BY p.avg_rating DESC, p.review_count DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function recalculateStats(int $placeId): bool
    {
        $sql = 'UPDATE places p
                SET avg_rating = (SELECT COALESCE(ROUND(AVG(r.rating), 2), 0) FROM reviews r WHERE r.place_id = :place_id AND r.status = "visible"),
                    review_count = (SELECT COUNT(*) FROM reviews r WHERE r.place_id = :place_id AND r.status = "visible")
                WHERE p.id = :place_id';
        return $this->executeSql($sql, ['place_id' => $placeId]);
    }

    public function allWithCategory(): array
    {
        return $this->fetchAllBySql('SELECT p.*, c.name AS category_name FROM places p JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC');
    }
}
