<?php

namespace Services;

use Models\UserFollowModel;
use Models\UserModel;

class FollowService
{
    private UserFollowModel $follows;
    private UserModel $users;
    private NotificationService $notifications;

    public function __construct()
    {
        $this->follows = new UserFollowModel();
        $this->users = new UserModel();
        $this->notifications = new NotificationService();
    }

    public function toggle(int $followerId, int $followingId): array
    {
        if ($followerId === $followingId) {
            return ['success' => false, 'message' => 'Bạn không thể follow chính mình.'];
        }

        $user = $this->users->find($followingId);
        if (!$user) {
            return ['success' => false, 'message' => 'User không tồn tại.'];
        }

        $followedBefore = $this->follows->exists($followerId, $followingId);
        $this->follows->toggle($followerId, $followingId);

        if (!$followedBefore) {
            $this->notifications->create($followingId, $followerId, 'follow_user', $followerId, 'đã bắt đầu theo dõi bạn');
        }

        return [
            'success' => true,
            'following' => !$followedBefore,
            'followers_count' => $this->follows->followersCount($followingId),
            'following_count' => $this->follows->followingCount($followerId),
        ];
    }

    public function isFollowing(int $followerId, int $followingId): bool
    {
        return $this->follows->exists($followerId, $followingId);
    }
}
