<section class="profile-header mb-4">
    <div class="card border-0 shadow-soft overflow-hidden">
        <div class="profile-cover"></div>
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row gap-4 align-items-lg-end">
                <img src="<?= asset($profile['avatar'] ?: config('app.default_avatar')) ?>" class="avatar avatar-xl profile-avatar" alt="avatar">
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start">
                        <div>
                            <h1 class="h2 mb-1"><?= e($profile['full_name']) ?></h1>
                            <div class="text-secondary mb-2">@<?= e($profile['username']) ?></div>
                            <p class="mb-3"><?= e($profile['bio'] ?: 'Food lover on FoodSpace.') ?></p>
                            <div class="d-flex flex-wrap gap-3 text-secondary small">
                                <span><strong><?= (int) $profile['review_total'] ?></strong> reviews</span>
                                <span><strong id="followers-count"><?= (int) $profile['followers_count'] ?></strong> followers</span>
                                <span><strong><?= (int) $profile['following_count'] ?></strong> following</span>
                                <span><?= status_badge($profile['status']) ?></span>
                            </div>
                        </div>
                        <?php if (is_logged_in() && (int) current_user()['id'] !== (int) $profile['id']): ?>
                            <button class="btn btn-primary rounded-pill js-follow-btn" data-url="<?= url('users/' . $profile['id'] . '/follow') ?>">
                                <?= $isFollowing ? 'Unfollow' : 'Follow' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Reviews</h2>
                <?php if (!$profile['reviews']): ?>
                    <div class="text-secondary">User này chưa có review nào.</div>
                <?php else: ?>
                    <?php foreach ($profile['reviews'] as $review): ?>
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <a href="<?= url('places/' . $review['place_slug']) ?>" class="fw-semibold text-decoration-none"><?= e($review['place_name']) ?></a>
                                <div><?= render_stars((float) $review['rating']) ?></div>
                            </div>
                            <div class="fw-semibold mb-2"><?= e($review['title']) ?></div>
                            <div class="text-secondary"><?= e(mb_strimwidth($review['content'], 0, 180, '...')) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Collections</h2>
                <?php if (!$profile['collections']): ?>
                    <div class="text-secondary">Chưa có collection công khai.</div>
                <?php else: ?>
                    <?php foreach ($profile['collections'] as $collection): ?>
                        <?php if ($collection['privacy'] === 'public' || (is_logged_in() && (int) current_user()['id'] === (int) $profile['id'])): ?>
                            <div class="rounded-4 bg-light p-3 mb-2">
                                <div class="fw-semibold"><?= e($collection['name']) ?></div>
                                <div class="small text-secondary"><?= e($collection['description']) ?></div>
                                <div class="small mt-1"><?= (int) $collection['place_total'] ?> places · <?= e($collection['privacy']) ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
