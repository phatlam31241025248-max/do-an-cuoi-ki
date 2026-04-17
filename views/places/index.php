<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <span class="pill-soft mb-2 d-inline-flex">Explore</span>
        <h1 class="h2 mb-1">Khám phá địa điểm ăn uống</h1>
        <p class="text-secondary mb-0">Tìm kiếm theo tên, địa chỉ, category và sắp xếp theo chất lượng review.</p>
    </div>
</div>

<div class="card border-0 shadow-soft mb-4">
    <div class="card-body">
        <form class="row g-3" method="get" action="<?= url('places') ?>">
            <div class="col-lg-5">
                <input class="form-control" type="text" name="keyword" value="<?= e($filters['keyword'] ?? '') ?>" placeholder="Tìm theo tên hoặc địa chỉ...">
            </div>
            <div class="col-lg-3">
                <select class="form-select" name="category">
                    <option value="">Tất cả category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= e($category['slug']) ?>" <?= ($filters['category'] ?? '') === $category['slug'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2">
                <select class="form-select" name="sort">
                    <option value="latest" <?= ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="rating" <?= ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Rating cao</option>
                    <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>Nhiều review</option>
                </select>
            </div>
            <div class="col-lg-2 d-grid">
                <button class="btn btn-primary">Lọc kết quả</button>
            </div>
        </form>
    </div>
</div>

<?php if (!$places): ?>
    <div class="empty-state text-center py-5">
        <i class="bi bi-search display-5 d-block mb-3"></i>
        <h3>Chưa tìm thấy địa điểm phù hợp</h3>
        <p class="text-secondary">Thử từ khóa khác hoặc đổi bộ lọc để khám phá thêm.</p>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($places as $place): ?>
            <div class="col-md-6 col-xl-4">
                <?php require base_path('views/components/place-card.php'); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($pagination['has_prev']): ?>
                <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['prev_page']])) ?>">Previous</a></li>
            <?php endif; ?>
            <li class="page-item active"><span class="page-link"><?= $pagination['page'] ?></span></li>
            <?php if ($pagination['has_next']): ?>
                <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['next_page']])) ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
