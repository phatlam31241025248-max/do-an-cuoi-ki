<?php

namespace Models;

use Core\BaseModel;

class UserFollowModel extends BaseModel
{
    protected string $table = 'user_follows';
    protected string $primaryKey = 'follower_id';
    protected array $fillable = ['follower_id', 'following_id', 'created_at'];

    public function exists(int $followerId, int $followingId): bool
    {
        return (bool) $this->fetchBySql('SELECT 1 FROM user_follows WHERE follower_id = :follower_id AND following_id = :following_id LIMIT 1', [
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ]);
    }

    public function toggle(int $followerId, int $followingId): bool
    {
        if ($this->exists($followerId, $followingId)) {
            return $this->executeSql('DELETE FROM user_follows WHERE follower_id = :follower_id AND following_id = :following_id', [
                'follower_id' => $followerId,
                'following_id' => $followingId,
            ]);
        }

        return $this->executeSql('INSERT INTO user_follows (follower_id, following_id, created_at) VALUES (:follower_id, :following_id, NOW())', [
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ]);
    }

    public function followersCount(int $userId): int
    {
        $row = $this->fetchBySql('SELECT COUNT(*) AS total FROM user_follows WHERE following_id = :user_id', ['user_id' => $userId]);
        return (int) ($row['total'] ?? 0);
    }

    public function followingCount(int $userId): int
    {
        $row = $this->fetchBySql('SELECT COUNT(*) AS total FROM user_follows WHERE follower_id = :user_id', ['user_id' => $userId]);
        return (int) ($row['total'] ?? 0);
    }
}
