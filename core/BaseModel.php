<?php

namespace Core;

use PDO;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $statement->execute(['id' => $id]);
        return $statement->fetch() ?: null;
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }

    public function create(array $data): int
    {
        $payload = array_intersect_key($data, array_flip($this->fillable));
        $columns = array_keys($payload);
        $placeholders = array_map(fn($column) => ':' . $column, $columns);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(',', $columns),
            implode(',', $placeholders)
        );

        $statement = $this->db->prepare($sql);
        $statement->execute($payload);

        return (int) $this->db->lastInsertId();
    }

    public function updateById(int $id, array $data): bool
    {
        $payload = array_intersect_key($data, array_flip($this->fillable));
        if (!$payload) {
            return false;
        }
        $assignments = implode(', ', array_map(fn($column) => "{$column} = :{$column}", array_keys($payload)));
        $payload['id'] = $id;

        $statement = $this->db->prepare("UPDATE {$this->table} SET {$assignments} WHERE {$this->primaryKey} = :id");
        return $statement->execute($payload);
    }

    public function deleteById(int $id): bool
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $statement->execute(['id' => $id]);
    }

    protected function fetchAllBySql(string $sql, array $params = []): array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    protected function fetchBySql(string $sql, array $params = []): ?array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetch() ?: null;
    }

    protected function executeSql(string $sql, array $params = []): bool
    {
        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }

    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
