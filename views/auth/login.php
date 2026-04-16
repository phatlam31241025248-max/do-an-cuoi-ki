<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card border-0 shadow-soft auth-card">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <span class="pill-soft mb-3 d-inline-flex">Welcome back</span>
                    <h1 class="h3 mb-2">Đăng nhập vào FoodSpace</h1>
                    <p class="text-secondary mb-0">Theo dõi reviewers, lưu địa điểm và chia sẻ cảm nhận thật.</p>
                </div>
                <form method="post" action="<?= url('login') ?>" class="d-grid gap-3">
                    <?= csrf_field() ?>
                    <div>
                        <label class="form-label">Email hoặc username</label>
                        <input type="text" class="form-control form-control-lg" name="login" value="<?= e(old('login')) ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control form-control-lg" name="password" required>
                    </div>
                    <button class="btn btn-primary btn-lg rounded-pill">Đăng nhập</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>
