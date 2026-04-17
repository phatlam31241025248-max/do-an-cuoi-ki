<div class="card border-0 shadow-soft mb-4">
    <div class="card-body p-4">
        <form class="row g-3 align-items-end" method="get" action="<?= url('admin/users') ?>">
            <div class="col-lg-8">
                <label class="form-label">Search user</label>
                <input class="form-control" name="keyword" value="<?= e($_GET['keyword'] ?? '') ?>" placeholder="Tên, username hoặc email">
            </div>
            <div class="col-lg-4 d-grid"><button class="btn btn-primary">Search</button></div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-soft">
    <div class="card-body p-4">
        <h1 class="h4 mb-3">Manage users</h1>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>User</th><th>Email</th><th>Status</th><th>Roles</th><th>Role assign</th><th>Status update</th></tr></thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= e($user['full_name']) ?></div>
                            <div class="small text-secondary">@<?= e($user['username']) ?></div>
                        </td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= status_badge($user['status']) ?></td>
                        <td><?= e($user['roles'] ?: 'user') ?></td>
                        <td>
                            <form method="post" action="<?= url('admin/users/' . $user['id'] . '/role') ?>" class="d-flex gap-2">
                                <?= csrf_field() ?>
                                <select class="form-select form-select-sm" name="roles[]">
                                    <option value="user">user</option>
                                    <option value="admin">admin</option>
                                </select>
                                <button class="btn btn-dark btn-sm rounded-pill">Save</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="<?= url('admin/users/' . $user['id'] . '/status') ?>" class="d-flex gap-2">
                                <?= csrf_field() ?>
                                <select class="form-select form-select-sm" name="status">
                                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>active</option>
                                    <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>banned</option>
                                </select>
                                <button class="btn btn-outline-secondary btn-sm rounded-pill">Apply</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
