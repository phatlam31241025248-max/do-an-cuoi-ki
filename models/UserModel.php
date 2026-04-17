<?php

namespace Models;

use Core\BaseModel;

class UserModel extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = [
        'full_name', 'username', 'email', 'password_hash', 'avatar', 'bio', 'status', 'created_at', 'updated_at'
    ];

    public function findByEmailOrUsername(string $value): ?array
    {
        return $this->fetchBySql(
            'SELECT * FROM users WHERE email = :value OR username = :value LIMIT 1',
            ['value' => $value]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return $this->fetchBySql('SELECT * FROM users WHERE username = :username LIMIT 1', ['username' => $username]);
    }

    public function getProfileByUsername(string $username): ?array
    {
        $sql = 'SELECT u.*,
                    (SELECT COUNT(*) FROM reviews r WHERE r.user_id = u.id AND r.status = "visible") AS review_total,
                    (SELECT COUNT(*) FROM user_follows uf WHERE uf.following_id = u.id) AS followers_count,
                    (SELECT COUNT(*) FROM user_follows uf WHERE uf.follower_id = u.id) AS following_count
                FROM users u
                WHERE u.username = :username
                LIMIT 1';

        return $this->fetchBySql($sql, ['username' => $username]);
    }

    public function getTopReviewers(int $limit = 5): array
    {
        $sql = 'SELECT u.id, u.full_name, u.username, u.avatar, COUNT(r.id) AS review_total,
                    COALESCE(AVG(r.rating), 0) AS avg_rating
                FROM users u
                JOIN reviews r ON r.user_id = u.id AND r.status = "visible"
                GROUP BY u.id
                ORDER BY review_total DESC, avg_rating DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function paginateForAdmin(string $keyword = ''): array
    {
        $sql = 'SELECT u.*, GROUP_CONCAT(r.name) AS roles
                FROM users u
                LEFT JOIN user_roles ur ON ur.user_id = u.id
                LEFT JOIN roles r ON r.id = ur.role_id';
        $params = [];
        if ($keyword !== '') {
            $sql .= ' WHERE u.full_name LIKE :keyword OR u.username LIKE :keyword OR u.email LIKE :keyword';
            $params['keyword'] = '%' . $keyword . '%';
        }
        $sql .= ' GROUP BY u.id ORDER BY u.created_at DESC';
        return $this->fetchAllBySql($sql, $params);
    }
}
