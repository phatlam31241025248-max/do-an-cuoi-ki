<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <span class="pill-soft mb-2 d-inline-flex">Saved places</span>
        <h1 class="h2 mb-1">My Bookmarks</h1>
        <p class="text-secondary mb-0">Những địa điểm bạn đã lưu để ghé lại sau.</p>
    </div>
</div>
<?php if (!$bookmarks): ?>
    <div class="empty-state text-center py-5">
        <i class="bi bi-bookmark-heart display-5 d-block mb-3"></i>
        <h3>Chưa có bookmark nào</h3>
        <p class="text-secondary">Hãy lưu vài địa điểm yêu thích để quay lại dễ hơn.</p>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($bookmarks as $place): ?>
            <div class="col-md-6 col-xl-4">
                <?php require base_path('views/components/place-card.php'); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
