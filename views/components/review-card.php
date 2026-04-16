<?php
$placeName = $review['place_name'] ?? 'Địa điểm';
$placeSlug = $review['place_slug'] ?? '';
$canManageReview = is_logged_in() && ((int) current_user()['id'] === (int) ($review['user_id'] ?? 0));
?>
<article class="card review-card border-0 shadow-soft mb-4" id="review-card-<?= (int) $review['id'] ?>">
    <div class="card-body p-4">
        <div class="d-flex align-items-start gap-3">
            <img src="<?= asset($review['avatar'] ?: config('app.default_avatar')) ?>" class="avatar avatar-md" alt="<?= e($review['username']) ?>">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="fw-semibold"><?= e($review['full_name']) ?> <span class="text-secondary">@<?= e($review['username']) ?></span></div>
                        <div class="text-secondary small">
                            <?= format_time_ago($review['created_at']) ?> ·
                            <?php if ($placeSlug): ?>
                                <a href="<?= url('places/' . $placeSlug) ?>" class="text-decoration-none"><?= e($placeName) ?></a>
                            <?php else: ?>
                                <span><?= e($placeName) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-end d-flex flex-column align-items-end gap-2">
                        <div>
                            <div><?= render_stars((float) $review['rating']) ?></div>
                            <small class="text-secondary"><?= number_format((float) $review['rank_score'], 1) ?> pts</small>
                        </div>
                        <?php if ($canManageReview): ?>
                            <form action="<?= url('reviews/' . $review['id'] . '/delete') ?>" method="post">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline-danger btn-sm rounded-pill">Xóa review</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <h5 class="mt-3 mb-2"><?= e($review['title']) ?></h5>
                <p class="text-secondary mb-3 review-content"><?= nl2br(e($review['content'])) ?></p>
                <?php
$reviewImage = $review['image'] ?? null;

if ($reviewImage && !preg_match('#^https?://#', $reviewImage)) {
    $reviewImage = '/' . ltrim($reviewImage, '/');
}
?>

<?php if (!empty($reviewImage)): ?>
    <img src="<?= e($reviewImage) ?>" class="review-image mb-3" alt="review image">
<?php endif; ?>
                <div class="d-flex flex-wrap align-items-center gap-2 action-row">
                    <?php if (is_logged_in()): ?>
                        <button type="button" class="btn btn-light btn-sm rounded-pill js-like-btn" data-url="<?= url('reviews/' . $review['id'] . '/like') ?>" data-review-id="<?= (int) $review['id'] ?>">
                            <i class="bi bi-hand-thumbs-up me-1"></i><span>Like</span>
                        </button>
                    <?php else: ?>
                        <a class="btn btn-light btn-sm rounded-pill" href="<?= url('login') ?>"><i class="bi bi-hand-thumbs-up me-1"></i>Like</a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-toggle="collapse" data-bs-target="#review-comments-<?= (int) $review['id'] ?>">
                        <i class="bi bi-chat-left-text me-1"></i>Comment
                    </button>
                    <?php if (is_logged_in()): ?>
                        <button type="button" class="btn btn-light btn-sm rounded-pill js-report-btn" data-url="<?= url('reviews/' . $review['id'] . '/report') ?>">
                            <i class="bi bi-flag me-1"></i>Report
                        </button>
                    <?php endif; ?>
                    <span class="ms-auto small text-secondary">
                        <span class="js-helpful-count"><?= (int) $review['helpful_count'] ?></span> helpful ·
                        <span class="js-comment-count"><?= (int) ($review['comment_count'] ?? 0) ?></span> comments
                    </span>
                </div>
                <div class="collapse mt-3" id="review-comments-<?= (int) $review['id'] ?>">
                    <div class="rounded-4 bg-light p-3">
                        <?php $commentService = new Services\CommentService(); $comments = $commentService->getByReview((int) $review['id']); ?>
                        <div class="comment-list mb-3" data-review-comments="<?= (int) $review['id'] ?>">
                            <?php if ($comments): foreach ($comments as $comment): ?>
                                <div class="d-flex gap-2 mb-2">
                                    <img src="<?= asset($comment['avatar'] ?: config('app.default_avatar')) ?>" class="avatar avatar-xs" alt="avatar">
                                    <div>
                                        <div class="small"><strong>@<?= e($comment['username']) ?></strong> · <span class="text-secondary"><?= format_time_ago($comment['created_at']) ?></span></div>
                                        <div><?= e($comment['content']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-secondary small">Chưa có bình luận nào. Hãy là người đầu tiên.</div>
                            <?php endif; ?>
                        </div>
                        <?php if (is_logged_in()): ?>
                            <form class="ajax-comment-form d-flex gap-2" action="<?= url('reviews/' . $review['id'] . '/comment') ?>" method="post" data-review-id="<?= (int) $review['id'] ?>">
                                <input type="hidden" name="_token" value="<?= e(Helpers\Csrf::token()) ?>">
                                <input type="text" class="form-control rounded-pill" name="content" placeholder="Viết bình luận của bạn..." required>
                                <button class="btn btn-primary rounded-pill">Gửi</button>
                            </form>
                        <?php else: ?>
                            <a href="<?= url('login') ?>" class="btn btn-outline-primary btn-sm rounded-pill">Đăng nhập để bình luận</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
