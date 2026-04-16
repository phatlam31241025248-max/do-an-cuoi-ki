<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-soft">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-4">Edit profile</h1>
                <form method="post" action="<?= url('profile/update') ?>" class="d-grid gap-3">
                    <?= csrf_field() ?>
                    <div>
                        <label class="form-label">Full name</label>
                        <input class="form-control" name="full_name" value="<?= e(current_user()['full_name']) ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" rows="4"><?= e(current_user()['bio'] ?? '') ?></textarea>
                    </div>
                    <button class="btn btn-primary rounded-pill">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
