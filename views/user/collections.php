<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Create collection</h1>
                <form method="post" action="<?= url('collections/store') ?>" class="d-grid gap-3">
                    <?= csrf_field() ?>
                    <input class="form-control" name="name" placeholder="Tên collection" required>
                    <textarea class="form-control" name="description" rows="3" placeholder="Mô tả ngắn"></textarea>
                    <select class="form-select" name="privacy">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                    <button class="btn btn-primary rounded-pill">Tạo collection</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">My Collections</h2>
                    <span class="text-secondary small"><?= count($collections) ?> collection(s)</span>
                </div>
                <?php if (!$collections): ?>
                    <div class="text-secondary">Bạn chưa có collection nào.</div>
                <?php else: ?>
                    <?php foreach ($collections as $collection): ?>
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between flex-wrap gap-3 align-items-start mb-2">
                                <div>
                                    <div class="fw-semibold"><?= e($collection['name']) ?></div>
                                    <div class="small text-secondary"><?= e($collection['description']) ?></div>
                                    <div class="small mt-1"><?= (int) $collection['place_total'] ?> places · <?= e($collection['privacy']) ?></div>
                                </div>
                                <form method="post" action="<?= url('collections/' . $collection['id'] . '/delete') ?>">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill">Delete</button>
                                </form>
                            </div>
                            <form method="post" action="<?= url('collections/' . $collection['id'] . '/update') ?>" class="row g-2 align-items-end">
                                <?= csrf_field() ?>
                                <div class="col-md-4"><input class="form-control" name="name" value="<?= e($collection['name']) ?>"></div>
                                <div class="col-md-4"><input class="form-control" name="description" value="<?= e($collection['description']) ?>"></div>
                                <div class="col-md-2">
                                    <select class="form-select" name="privacy">
                                        <option value="public" <?= $collection['privacy'] === 'public' ? 'selected' : '' ?>>Public</option>
                                        <option value="private" <?= $collection['privacy'] === 'private' ? 'selected' : '' ?>>Private</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid"><button class="btn btn-dark rounded-pill">Save</button></div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
