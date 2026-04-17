<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\NotificationService;

class NotificationController extends BaseController
{
    private NotificationService $notifications;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new NotificationService();
    }

    public function index(): void
    {
        $this->view('notifications/index', [
            'title' => 'Notifications',
            'notifications' => $this->notifications->getByUser((int) current_user()['id']),
        ]);
    }

    public function read(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $this->notifications->markAsRead((int) $this->request->input('notification_id'), (int) current_user()['id']);
        $this->jsonSuccess('Đã đánh dấu là đã đọc.', [
            'unread_count' => $this->notifications->unreadCount((int) current_user()['id']),
        ]);
    }

    public function readAll(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            $this->jsonError('CSRF token không hợp lệ.', [], 419);
        }

        $this->notifications->markAllAsRead((int) current_user()['id']);
        $this->jsonSuccess('Đã đánh dấu tất cả là đã đọc.', ['unread_count' => 0]);
    }
}
