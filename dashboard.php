<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <span class="pill-soft mb-2 d-inline-flex">Admin</span>
        <h1 class="h2 mb-1">Dashboard</h1>
        <p class="text-secondary mb-0">Tổng quan nhanh toàn bộ hệ thống FoodSpace.</p>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="stat-label">Users</div><div class="stat-value"><?= (int) $stats['user_count'] ?></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="stat-label">Places</div><div class="stat-value"><?= (int) $stats['place_count'] ?></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="stat-label">Reviews</div><div class="stat-value"><?= (int) $stats['review_count'] ?></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="stat-label">Reports</div><div class="stat-value"><?= (int) $stats['report_count'] ?></div></div></div>
</div>
<div class="card border-0 shadow-soft">
    <div class="card-body p-4">
        <h2 class="h4 mb-3">Latest reviews</h2>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>ID</th><th>User</th><th>Place</th><th>Rating</th><th>Status</th><th>Reports</th></tr></thead>
                <tbody>
                <?php foreach (array_slice($stats['latest_reviews'], 0, 10) as $review): ?>
                    <tr>
                        <td>#<?= (int) $review['id'] ?></td>
                        <td>@<?= e($review['username']) ?></td>
                        <td><?= e($review['place_name']) ?></td>
                        <td><?= (int) $review['rating'] ?></td>
                        <td><?= status_badge($review['status']) ?></td>
                        <td><?= (int) $review['report_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
