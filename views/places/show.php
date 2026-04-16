<?php
$heroImage = ($place['cover_image'] ?: $place['thumbnail']) ?: config('app.default_place_image');

if ($heroImage && !preg_match('#^https?://#', $heroImage)) {
    $heroImage = '/' . ltrim($heroImage, '/');
}
?>

<section class="place-hero mb-4" style="background-image: linear-gradient(120deg, rgba(15,23,42,.72), rgba(15,23,42,.4)), url('<?= e($heroImage) ?>');">
    <div class="row g-4 align-items-end">
        <div class="col-lg-8 text-white">
            <span class="badge rounded-pill text-bg-light text-dark mb-3"><?= e($place['category_name']) ?></span>
            <h1 class="display-5 fw-bold mb-3"><?= e($place['name']) ?></h1>
            <div class="d-grid gap-2 text-white-50">
                <div><i class="bi bi-geo-alt me-2"></i><?= e($place['address']) ?></div>
                <div><i class="bi bi-telephone me-2"></i><?= e($place['phone'] ?: 'Đang cập nhật') ?></div>
                <div><i class="bi bi-clock me-2"></i><?= e($place['open_hours'] ?: 'Đang cập nhật') ?></div>
                <div><i class="bi bi-wallet2 me-2"></i><?= e(format_price_range($place['price_range'])) ?></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="place-summary-card bg-white shadow-soft rounded-5 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="small text-secondary">Average rating</div>
                        <div class="display-6 fw-bold"><?= number_format((float) $place['avg_rating'], 1) ?></div>
                    </div>
                    <div class="text-end">
                        <div><?= render_stars((float) $place['avg_rating']) ?></div>
                        <div class="small text-secondary"><?= (int) $place['review_count'] ?> reviews</div>
                    </div>
                </div>
                <?php if (is_logged_in()): ?>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary rounded-pill js-bookmark-btn" data-url="<?= url('places/' . $place['id'] . '/bookmark') ?>">
                            <i class="bi bi-bookmark-heart me-1"></i>Bookmark place
                        </button>
                        <?php if ($collections): ?>
                            <?php foreach ($collections as $collection): ?>
                                <button class="btn btn-light rounded-pill js-collection-toggle-btn" data-url="<?= url('collections/' . $collection['id'] . '/toggle-place') ?>" data-place-id="<?= (int) $place['id'] ?>">
                                    <i class="bi bi-folder-plus me-1"></i><?= e($collection['name']) ?>
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <a href="<?= url('login') ?>" class="btn btn-primary rounded-pill w-100">Đăng nhập để lưu địa điểm</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Giới thiệu</h2>
                <p class="text-secondary mb-0"><?= nl2br(e($place['description'] ?: 'Địa điểm này đang chờ cộng đồng bổ sung trải nghiệm thực tế.')) ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-soft mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Reviews</h2>
                <?php if (!$place['reviews']): ?>
                    <div class="empty-state py-4 text-center">
                        <i class="bi bi-chat-heart display-5 d-block mb-3"></i>
                        <h3>Chưa có review nào</h3>
                        <p class="text-secondary mb-0">Hãy là người đầu tiên chia sẻ trải nghiệm thật tại địa điểm này.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($place['reviews'] as $review): ?>
                        <?php require base_path('views/components/review-card.php'); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft sticky-column">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Viết review</h2>
                <?php if (is_logged_in()): ?>
                    <form method="post" action="<?= url('reviews/store') ?>" enctype="multipart/form-data" class="d-grid gap-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="place_id" value="<?= (int) $place['id'] ?>">
                        <input type="hidden" name="place_slug" value="<?= e($place['slug']) ?>">
                        <div>
                            <label class="form-label">Rating</label>
                            <select class="form-select" name="rating" required>
                                <option value="">Chọn số sao</option>
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <option value="<?= $i ?>"><?= $i ?> sao</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Tiêu đề</label>
                            <input class="form-control" name="title" required>
                        </div>
                        <div>
                            <label class="form-label">Nội dung</label>
                            <textarea class="form-control" rows="5" name="content" required></textarea>
                        </div>
                        <div>
                            <label class="form-label">Ảnh review</label>
                            <input class="form-control js-image-input" type="file" name="image" accept="image/*" data-preview-target="#place-review-preview">
                            <div class="form-text">JPG, PNG, WEBP hoặc GIF. Tối đa 5MB.</div>
                            <img id="place-review-preview" class="image-preview image-preview-cover mt-2 d-none" alt="Review preview">
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary rounded-pill">Đăng review</button>
                            <a href="<?= url('review-studio') ?>" class="btn btn-outline-dark rounded-pill">Tạo địa điểm mới + review đầu tiên</a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-secondary mb-3">Bạn cần đăng nhập để viết review, like, comment và bookmark.</div>
                    <a href="<?= url('login') ?>" class="btn btn-primary rounded-pill w-100">Đăng nhập ngay</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
