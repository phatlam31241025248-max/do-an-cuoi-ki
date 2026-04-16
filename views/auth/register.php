<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-soft auth-card">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <span class="pill-soft mb-3 d-inline-flex">Join FoodSpace</span>
                    <h1 class="h3 mb-2">Tạo tài khoản mới</h1>
                    <p class="text-secondary mb-0">Bắt đầu hành trình khám phá và review địa điểm ăn uống của bạn.</p>
                </div>
                <form method="post" action="<?= url('register') ?>" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-md-6">
                        <label class="form-label">Full name</label>
                        <input type="text" class="form-control" name="full_name" value="<?= e(old('full_name')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?= e(old('username')) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= e(old('email')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-lg rounded-pill w-100">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
