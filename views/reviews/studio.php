<section class="mb-4">
    <div class="card border-0 shadow-soft studio-hero">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="pill-soft mb-3 d-inline-flex">Review Studio</span>
                    <h1 class="h2 mb-2">Viết review theo cách phù hợp với bạn</h1>
                    <p class="text-secondary mb-0">Chọn địa điểm đã có để đăng nhanh, hoặc tạo địa điểm mới nếu nơi bạn muốn chia sẻ chưa xuất hiện trên FoodSpace.</p>
                </div>
                <div class="col-lg-4">
                    <div class="studio-checklist rounded-4 p-3 bg-light">
                        <div class="small fw-semibold mb-2">Mẹo nhỏ</div>
                        <ul class="small mb-0 ps-3">
                            <li>Tiêu đề ngắn gọn, đúng cảm nhận thực tế.</li>
                            <li>Ảnh thật sẽ giúp bài review nổi bật hơn.</li>
                            <li>Thông tin địa điểm càng rõ thì càng dễ tìm lại.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4 p-lg-5">
                <form method="post" action="<?= url('review-studio/store') ?>" enctype="multipart/form-data" class="d-grid gap-4">
                    <?= csrf_field() ?>

                    <div>
                        <label class="form-label fw-semibold">Bạn muốn review theo kiểu nào?</label>
                        <div class="review-mode-switch d-flex flex-wrap gap-2">
                            <input class="btn-check" type="radio" name="place_mode" id="mode-existing" value="existing" <?= old('place_mode', 'existing') !== 'new' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-dark rounded-pill" for="mode-existing">Địa điểm có sẵn</label>

                            <input class="btn-check" type="radio" name="place_mode" id="mode-new" value="new" <?= old('place_mode') === 'new' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-dark rounded-pill" for="mode-new">Tạo địa điểm mới</label>
                        </div>
                    </div>

                    <div class="mode-panel mode-existing-panel">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Chọn địa điểm có sẵn</label>
                                <select class="form-select" name="place_id">
                                    <option value="">-- Chọn địa điểm --</option>
                                    <?php foreach ($places as $place): ?>
                                        <option value="<?= (int) $place['id'] ?>" <?= (string) old('place_id') === (string) $place['id'] ? 'selected' : '' ?>>
                                            <?= e($place['name']) ?> — <?= e($place['address']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mode-panel mode-new-panel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên địa điểm mới</label>
                                <input class="form-control" name="new_place_name" value="<?= e(old('new_place_name')) ?>" placeholder="Ví dụ: Bún Đậu Cô Ba">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="new_category_id">
                                    <option value="">-- Chọn category --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= (int) $category['id'] ?>" <?= (string) old('new_category_id') === (string) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ</label>
                                <input class="form-control" name="new_address" value="<?= e(old('new_address')) ?>" placeholder="Số nhà, đường, quận/huyện, thành phố">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input class="form-control" name="new_phone" value="<?= e(old('new_phone')) ?>" placeholder="090...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giờ mở cửa</label>
                                <input class="form-control" name="new_open_hours" value="<?= e(old('new_open_hours')) ?>" placeholder="08:00 - 22:00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Khoảng giá</label>
                                <input class="form-control" name="new_price_range" value="<?= e(old('new_price_range')) ?>" placeholder="40.000đ - 120.000đ">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh đại diện địa điểm</label>
                                <input class="form-control js-image-input" type="file" name="new_thumbnail" accept="image/*" data-preview-target="#studio-thumb-preview">
                                <div class="form-text">JPG, PNG, WEBP hoặc GIF. Tối đa 5MB. Nếu bạn chưa chọn cover riêng, ảnh này sẽ được ưu tiên hiển thị ở trang chi tiết.</div>
                                <img id="studio-thumb-preview" class="image-preview mt-2 d-none" alt="Thumbnail preview">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh cover địa điểm</label>
                                <input class="form-control js-image-input" type="file" name="new_cover_image" accept="image/*" data-preview-target="#studio-cover-preview">
                                <div class="form-text">Có thể bỏ trống nếu bạn chưa có ảnh cover.</div>
                                <img id="studio-cover-preview" class="image-preview image-preview-cover mt-2 d-none" alt="Cover preview">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mô tả địa điểm</label>
                                <textarea class="form-control" rows="4" name="new_description" placeholder="Mô tả ngắn về không gian, món nổi bật, điểm đặc biệt..."><?= e(old('new_description')) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="my-0">

                    <div>
                        <h2 class="h5 mb-3">Nội dung bài review</h2>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Rating</label>
                                <select class="form-select" name="rating" required>
                                    <option value="">Chọn số sao</option>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>" <?= (string) old('rating') === (string) $i ? 'selected' : '' ?>><?= $i ?> sao</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Tiêu đề review</label>
                                <input class="form-control" name="title" value="<?= e(old('title')) ?>" placeholder="Ví dụ: Quán đẹp, đồ ăn ổn nhưng hơi đông" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nội dung review</label>
                                <textarea class="form-control" rows="6" name="content" placeholder="Chia sẻ trải nghiệm thật của bạn..." required><?= e(old('content')) ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ảnh review</label>
                                <input class="form-control js-image-input" type="file" name="image" accept="image/*" data-preview-target="#studio-review-preview">
                                <div class="form-text">Bạn có thể bỏ trống nếu chỉ muốn đăng nội dung chữ.</div>
                                <img id="studio-review-preview" class="image-preview image-preview-cover mt-2 d-none" alt="Review preview">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary rounded-pill px-4">Đăng bài review</button>
                        <a href="<?= url('places') ?>" class="btn btn-outline-dark rounded-pill px-4">Xem danh sách địa điểm</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Một bài review dễ đọc thường có</h2>
                <div class="d-grid gap-3 small text-secondary">
                    <div><strong class="text-dark">Món nổi bật:</strong> món nào đáng thử hoặc chưa ổn.</div>
                    <div><strong class="text-dark">Không gian:</strong> rộng, yên tĩnh, đông khách hay phù hợp check-in.</div>
                    <div><strong class="text-dark">Giá và trải nghiệm:</strong> mức giá, tốc độ phục vụ, điểm bạn muốn quay lại.</div>
                </div>
            </div>
        </div>
    </div>
</div>
