<?php

namespace Services;

use Models\NotificationModel;

class NotificationService
{
    private NotificationModel $notifications;

    public function __construct()
    {
        $this->notifications = new NotificationModel();
    }

    public function create(int $userId, int $actorId, string $type, int $referenceId, string $message): int
    {
        return $this->notifications->create([
            'user_id' => $userId,
            'actor_id' => $actorId,
            'type' => $type,
            'reference_id' => $referenceId,
            'message' => $message,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getByUser(int $userId): array
    {
        return $this->notifications->getByUser($userId);
    }

    public function unreadCount(int $userId): int
    {
        return $this->notifications->unreadCount($userId);
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return $this->notifications->markAsRead($notificationId, $userId);
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->notifications->markAllAsRead($userId);
    }
}
