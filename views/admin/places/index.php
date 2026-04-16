<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Create place</h1>
                <form method="post" action="<?= url('admin/places/store') ?>" enctype="multipart/form-data" class="d-grid gap-3">
                    <?= csrf_field() ?>
                    <select class="form-select" name="category_id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>"><?= e($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input class="form-control" name="name" placeholder="Tên địa điểm" required>
                    <input class="form-control" name="address" placeholder="Địa chỉ" required>
                    <textarea class="form-control" name="description" rows="4" placeholder="Mô tả"></textarea>
                    <input class="form-control" name="phone" placeholder="Số điện thoại">
                    <input class="form-control" name="open_hours" placeholder="Giờ mở cửa">
                    <input class="form-control" name="price_range" placeholder="40.000đ - 120.000đ">
                    <div>
                        <label class="form-label mb-1">Thumbnail</label>
                        <input class="form-control js-image-input" type="file" name="thumbnail" accept="image/*" data-preview-target="#admin-thumb-preview">
                        <img id="admin-thumb-preview" class="image-preview mt-2 d-none" alt="Thumbnail preview">
                    </div>
                    <div>
                        <label class="form-label mb-1">Cover image</label>
                        <input class="form-control js-image-input" type="file" name="cover_image" accept="image/*" data-preview-target="#admin-cover-preview">
                        <img id="admin-cover-preview" class="image-preview image-preview-cover mt-2 d-none" alt="Cover preview">
                    </div>
                    <button class="btn btn-primary rounded-pill">Create</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Places table</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Name</th><th>Category</th><th>Rating</th><th>Reviews</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php foreach ($places as $place): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= asset($place['thumbnail'] ?: config('app.default_place_image')) ?>" alt="thumb" style="width:56px;height:56px;object-fit:cover;border-radius:16px;">
                                        <div>
                                            <div class="fw-semibold"><?= e($place['name']) ?></div>
                                            <div class="small text-secondary"><?= e($place['address']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= e($place['category_name']) ?></td>
                                <td><?= number_format((float) $place['avg_rating'], 1) ?></td>
                                <td><?= (int) $place['review_count'] ?></td>
                                <td>
                                    <form method="post" action="<?= url('admin/places/' . $place['id'] . '/delete') ?>">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-outline-danger btn-sm rounded-pill">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
