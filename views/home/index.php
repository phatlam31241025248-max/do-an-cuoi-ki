<section class="hero-card mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="pill-soft mb-3 d-inline-flex">Food reviews & local discovery</span>
            <h1 class="display-5 fw-bold mb-3">Tìm chỗ ăn ngon, lưu lại địa điểm hay và chia sẻ trải nghiệm của bạn.</h1>
            <p class="lead text-secondary mb-4">FoodSpace kết hợp social feed, review địa điểm và bộ sưu tập cá nhân để việc khám phá quán xá trở nên trực quan hơn mỗi ngày.</p>
            <div class="d-flex flex-wrap gap-2">
                <?php if (is_logged_in()): ?>
                    <a href="<?= url('review-studio') ?>" class="btn btn-primary btn-lg rounded-pill px-4"><i class="bi bi-pencil-square me-2"></i>Viết review</a>
                    <a href="<?= url('places') ?>" class="btn btn-outline-dark btn-lg rounded-pill px-4">Khám phá địa điểm</a>
                <?php else: ?>
                    <a href="<?= url('register') ?>" class="btn btn-primary btn-lg rounded-pill px-4">Tạo tài khoản</a>
                    <a href="<?= url('places') ?>" class="btn btn-outline-dark btn-lg rounded-pill px-4">Xem địa điểm</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="hero-metrics">
                <div class="metric-card">
                    <div class="metric-label">Địa điểm nổi bật</div>
                    <div class="metric-value"><?= count($hotPlaces) ?>+</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Top reviewers</div>
                    <div class="metric-value"><?= count($topReviewers) ?>+</div>
                </div>
                <div class="metric-card metric-highlight">
                    <div class="metric-label">Collections</div>
                    <div class="metric-text">Lưu quán theo từng dịp: ăn trưa, cafe cuối tuần, đi cùng bạn bè.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-soft composer-card">
            <div class="card-body p-4 p-lg-5 d-flex flex-column flex-lg-row align-items-lg-center gap-4 justify-content-between">
                <div>
                    <span class="pill-soft mb-2 d-inline-flex">Start sharing</span>
                    <h2 class="h3 mb-2">Viết review cho địa điểm quen thuộc hoặc thêm địa điểm mới</h2>
                    <p class="text-secondary mb-0">Bạn có thể chọn một địa điểm có sẵn hoặc tạo địa điểm mới ngay trong Review Studio rồi đăng bài trong cùng một lần.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <?php if (is_logged_in()): ?>
                        <a href="<?= url('review-studio') ?>" class="btn btn-primary rounded-pill px-4">Mở Review Studio</a>
                        <?php if (has_role('admin')): ?>
                            <a href="<?= url('admin/dashboard') ?>" class="btn btn-outline-dark rounded-pill px-4">Admin Panel</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= url('login') ?>" class="btn btn-primary rounded-pill px-4">Đăng nhập để bắt đầu</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <aside class="col-xl-3 d-none d-xl-block">
        <div class="sticky-column d-grid gap-3">
            <div class="card border-0 shadow-soft">
                <div class="card-body">
                    <div class="section-title mb-3">Categories</div>
                    <div class="d-grid gap-2">
                        <?php foreach ($categories as $category): ?>
                            <a href="<?= url('places?category=' . $category['slug']) ?>" class="btn btn-light text-start rounded-4"><?= e($category['name']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-soft">
                <div class="card-body">
                    <div class="section-title mb-3"><?= has_role('admin') ? 'Admin shortcuts' : 'Quick links' ?></div>
                    <div class="list-group list-group-flush">
                        <?php if (has_role('admin')): ?>
                            <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('admin/dashboard') ?>">Dashboard tổng quan</a>
                            <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('admin/places') ?>">Quản lý địa điểm</a>
                            <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('admin/users') ?>">Quản lý người dùng</a>
                            <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('admin/reviews') ?>">Duyệt review & report</a>
                        <?php else: ?>
                            <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('places?sort=popular') ?>">Nhiều review nhất</a>
                            <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('places?sort=rating') ?>">Rating cao nhất</a>
                            <?php if (is_logged_in()): ?>
                                <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('review-studio') ?>">Viết review mới</a>
                                <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('my-reviews') ?>">My Reviews</a>
                                <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('my-bookmarks') ?>">My Bookmarks</a>
                                <a class="list-group-item list-group-item-action border-0 px-0" href="<?= url('collections') ?>">My Collections</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <section class="col-xl-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h4 mb-1">Social Review Feed</h2>
                <p class="text-secondary mb-0">Những review mới và nổi bật từ cộng đồng FoodSpace.</p>
            </div>
        </div>
        <?php foreach ($feed as $review): ?>
            <?php require base_path('views/components/review-card.php'); ?>
        <?php endforeach; ?>
    </section>
    <aside class="col-xl-3">
        <div class="sticky-column d-grid gap-3">
            <?php if (has_role('admin')): ?>
                <div class="card border-0 shadow-soft">
                    <div class="card-body">
                        <div class="section-title mb-3">Quản trị nhanh</div>
                        <div class="d-grid gap-2">
                            <a href="<?= url('admin/dashboard') ?>" class="btn btn-light text-start rounded-4">Dashboard</a>
                            <a href="<?= url('admin/places') ?>" class="btn btn-light text-start rounded-4">Places</a>
                            <a href="<?= url('admin/reviews') ?>" class="btn btn-light text-start rounded-4">Reviews & Reports</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-soft">
                    <div class="card-body">
                        <div class="section-title mb-3">Gợi ý nhanh</div>
                        <div class="small text-secondary d-grid gap-2">
                            <div>Tạo collection cho từng dịp đi ăn để dễ lưu và chia sẻ.</div>
                            <div>Theo dõi reviewer bạn thích để xem review mới trong Following Feed.</div>
                            <div>Dùng bookmark để lưu lại quán muốn ghé vào lần sau.</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="card border-0 shadow-soft">
                <div class="card-body">
                    <div class="section-title mb-3">Top reviewers</div>
                    <?php foreach ($topReviewers as $user): ?>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="<?= asset($user['avatar'] ?: config('app.default_avatar')) ?>" class="avatar avatar-sm" alt="avatar">
                            <div class="flex-grow-1">
                                <a href="<?= url('profile/' . $user['username']) ?>" class="text-decoration-none fw-semibold text-dark">@<?= e($user['username']) ?></a>
                                <div class="small text-secondary"><?= (int) $user['review_total'] ?> reviews · ⭐ <?= number_format((float) $user['avg_rating'], 1) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card border-0 shadow-soft">
                <div class="card-body">
                    <div class="section-title mb-3">Địa điểm hot</div>
                    <?php foreach ($hotPlaces as $place): ?>
                        <div class="mb-3 pb-3 border-bottom last-border-0">
                            <a href="<?= url('places/' . $place['slug']) ?>" class="text-decoration-none fw-semibold text-dark"><?= e($place['name']) ?></a>
                            <div class="small text-secondary"><?= e($place['category_name']) ?> · <?= number_format((float) $place['avg_rating'], 1) ?> sao</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card border-0 shadow-soft">
                <div class="card-body">
                    <div class="section-title mb-3">Collection nổi bật</div>
                    <?php foreach ($featuredCollections as $collection): ?>
                        <div class="rounded-4 bg-light p-3 mb-2">
                            <div class="fw-semibold"><?= e($collection['name']) ?></div>
                            <div class="small text-secondary">by @<?= e($collection['username']) ?> · <?= (int) $collection['place_total'] ?> places</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </aside>
</div>
