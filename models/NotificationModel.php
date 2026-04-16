<?php

namespace Models;

use Core\BaseModel;

class NotificationModel extends BaseModel
{
    protected string $table = 'notifications';
    protected array $fillable = ['user_id', 'actor_id', 'type', 'reference_id', 'message', 'is_read', 'created_at'];

    public function getByUser(int $userId): array
    {
        $sql = 'SELECT n.*, u.username AS actor_username, u.avatar AS actor_avatar
                FROM notifications n
                LEFT JOIN users u ON u.id = n.actor_id
                WHERE n.user_id = :user_id
                ORDER BY n.created_at DESC';
        return $this->fetchAllBySql($sql, ['user_id' => $userId]);
    }

    public function unreadCount(int $userId): int
    {
        $row = $this->fetchBySql('SELECT COUNT(*) AS total FROM notifications WHERE user_id = :user_id AND is_read = 0', ['user_id' => $userId]);
        return (int) ($row['total'] ?? 0);
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return $this->executeSql('UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id', [
            'id' => $notificationId,
            'user_id' => $userId,
        ]);
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->executeSql('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id', ['user_id' => $userId]);
    }
}
