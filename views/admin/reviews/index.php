<div class="row g-4">
    <div class="col-xl-7">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Reviews moderation</h1>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Review</th><th>User</th><th>Status</th><th>Reports</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= e($review['title']) ?></div>
                                    <div class="small text-secondary"><?= e($review['place_name']) ?></div>
                                </td>
                                <td>@<?= e($review['username']) ?></td>
                                <td><?= status_badge($review['status']) ?></td>
                                <td><?= (int) $review['report_count'] ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form method="post" action="<?= url('admin/reviews/' . $review['id'] . '/hide') ?>"><?= csrf_field() ?><button class="btn btn-outline-secondary btn-sm rounded-pill">Hide</button></form>
                                        <form method="post" action="<?= url('admin/reviews/' . $review['id'] . '/delete') ?>"><?= csrf_field() ?><button class="btn btn-outline-danger btn-sm rounded-pill">Delete</button></form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Report queue</h2>
                <?php foreach ($reports as $report): ?>
                    <div class="border rounded-4 p-3 mb-3">
                        <div class="fw-semibold"><?= e($report['review_title']) ?></div>
                        <div class="small text-secondary mb-2">Reported by @<?= e($report['reporter_username']) ?> · <?= e($report['place_name']) ?></div>
                        <div class="mb-2"><?= e($report['reason']) ?></div>
                        <div class="small text-secondary">Status review: <?= e($report['review_status']) ?> · <?= format_time_ago($report['created_at']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
