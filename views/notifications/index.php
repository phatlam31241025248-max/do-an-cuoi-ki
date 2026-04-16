<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="pill-soft mb-2 d-inline-flex">Inbox</span>
        <h1 class="h2 mb-1">Notifications</h1>
        <p class="text-secondary mb-0">Theo dõi tương tác mới nhất với review và profile của bạn.</p>
    </div>
    <button class="btn btn-outline-dark rounded-pill js-read-all-btn" data-url="<?= url('notifications/read-all') ?>">Đánh dấu tất cả đã đọc</button>
</div>
<?php if (!$notifications): ?>
    <div class="empty-state text-center py-5">
        <i class="bi bi-bell-slash display-5 d-block mb-3"></i>
        <h3>Chưa có thông báo nào</h3>
        <p class="text-secondary">Khi có ai đó like, comment hoặc follow bạn, chúng sẽ xuất hiện tại đây.</p>
    </div>
<?php else: ?>
    <div class="d-grid gap-3">
        <?php foreach ($notifications as $notification): ?>
            <div class="card border-0 shadow-soft notification-item <?= !$notification['is_read'] ? 'notification-unread' : '' ?>">
                <div class="card-body d-flex gap-3 align-items-start">
                    <img src="<?= asset($notification['actor_avatar'] ?: config('app.default_avatar')) ?>" class="avatar avatar-sm" alt="avatar">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">@<?= e($notification['actor_username'] ?: 'system') ?> <span class="fw-normal text-secondary"><?= e($notification['message']) ?></span></div>
                        <div class="small text-secondary"><?= format_time_ago($notification['created_at']) ?></div>
                    </div>
                    <?php if (!(int) $notification['is_read']): ?>
                        <button class="btn btn-sm btn-light rounded-pill js-read-btn" data-url="<?= url('notifications/read') ?>" data-notification-id="<?= (int) $notification['id'] ?>">Đã đọc</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
