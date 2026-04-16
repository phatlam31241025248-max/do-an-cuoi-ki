<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Create category</h1>
                <form method="post" action="<?= url('admin/categories/store') ?>" class="d-grid gap-3">
                    <?= csrf_field() ?>
                    <input class="form-control" name="name" placeholder="Tên category" required>
                    <textarea class="form-control" name="description" rows="4" placeholder="Mô tả"></textarea>
                    <button class="btn btn-primary rounded-pill">Create</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">All categories</h2>
                <?php foreach ($categories as $category): ?>
                    <div class="border rounded-4 p-3 mb-3">
                        <form class="row g-2 align-items-end" method="post" action="<?= url('admin/categories/' . $category['id'] . '/update') ?>">
                            <?= csrf_field() ?>
                            <div class="col-md-4"><input class="form-control" name="name" value="<?= e($category['name']) ?>"></div>
                            <div class="col-md-5"><input class="form-control" name="description" value="<?= e($category['description']) ?>"></div>
                            <div class="col-md-3 d-flex gap-2">
                                <button class="btn btn-dark rounded-pill w-100">Save</button>
                            </div>
                        </form>
                        <form class="mt-2" method="post" action="<?= url('admin/categories/' . $category['id'] . '/delete') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-danger btn-sm rounded-pill">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
