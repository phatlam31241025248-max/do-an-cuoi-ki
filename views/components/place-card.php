<?php
$placeImage = ($place['thumbnail'] ?: $place['cover_image']) ?: config('app.default_place_image');

if ($placeImage && !preg_match('#^https?://#', $placeImage)) {
    $placeImage = '/' . ltrim($placeImage, '/');
}
?>

<article class="card place-card border-0 shadow-soft h-100">
    <img src="<?= e($placeImage) ?>" class="card-img-top place-thumb" alt="<?= e($place['name']) ?>">
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
            <div>
                <span class="badge rounded-pill text-bg-light border"><?= e($place['category_name'] ?? 'Ẩm thực') ?></span>
                <h5 class="mt-2 mb-1">
                    <a class="text-decoration-none text-dark" href="<?= url('places/' . $place['slug']) ?>">
                        <?= e($place['name']) ?>
                    </a>
                </h5>
            </div>
            <span class="rating-pill">
                <i class="bi bi-star-fill text-warning"></i> <?= number_format((float) $place['avg_rating'], 1) ?>
            </span>
        </div>
        <p class="text-secondary small mb-2">
            <i class="bi bi-geo-alt me-1"></i><?= e($place['address']) ?>
        </p>
        <div class="mt-auto d-flex justify-content-between align-items-center small text-secondary">
            <span><i class="bi bi-chat-square-text me-1"></i><?= (int) $place['review_count'] ?> reviews</span>
            <span><?= e(format_price_range($place['price_range'] ?? '')) ?></span>
        </div>
    </div>
</article>