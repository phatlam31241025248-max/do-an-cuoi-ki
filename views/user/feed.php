<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <span class="pill-soft mb-2 d-inline-flex"><?= ($feedTitle ?? '') === 'My Reviews' ? 'My content' : 'Social graph' ?></span>
        <h1 class="h2 mb-1"><?= e($feedTitle ?? 'Feed') ?></h1>
        <p class="text-secondary mb-0"><?= ($feedTitle ?? '') === 'My Reviews' ? 'Tất cả bài review bạn đã đăng trên FoodSpace.' : 'Review mới từ những người bạn theo dõi trên FoodSpace.' ?></p>
    </div>
    <?php if (($feedTitle ?? '') === 'My Reviews'): ?>
        <a href="<?= url('review-studio') ?>" class="btn btn-primary rounded-pill"><i class="bi bi-plus-circle me-1"></i>Đăng review mới</a>
    <?php endif; ?>
</div>
<?php if (!$feed): ?>
    <div class="empty-state text-center py-5">
        <i class="bi bi-people display-5 d-block mb-3"></i>
        <?php if (($feedTitle ?? '') === 'My Reviews'): ?>
            <h3>Bạn chưa có review nào</h3>
            <p class="text-secondary">Hãy bắt đầu bằng cách mở Review Studio và đăng bài đầu tiên.</p>
        <?php else: ?>
            <h3>Feed của bạn còn trống</h3>
            <p class="text-secondary">Hãy follow thêm food reviewers để nhận review mới trong feed.</p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php foreach ($feed as $review): ?>
        <?php require base_path('views/components/review-card.php'); ?>
    <?php endforeach; ?>
<?php endif; ?>
