<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(Helpers\Csrf::token()) ?>">
    <title><?= e(($title ?? 'Admin') . ' | FoodSpace') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= asset('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="admin-body">
<div class="container-fluid">
    <div class="row min-vh-100">
        <aside class="col-lg-2 admin-sidebar p-4">
            <a href="<?= url('admin/dashboard') ?>" class="brand brand-admin d-flex align-items-center gap-2 text-decoration-none mb-4">
                <span class="brand-mark"><i class="bi bi-geo-alt-fill"></i></span>
                <div>
                    <div class="brand-text">FoodSpace</div>
                    <small class="text-secondary">Admin panel</small>
                </div>
            </a>
            <div class="nav flex-column gap-2">
                <a class="admin-link" href="<?= url('admin/dashboard') ?>"><i class="bi bi-grid me-2"></i>Dashboard</a>
                <a class="admin-link" href="<?= url('admin/categories') ?>"><i class="bi bi-tags me-2"></i>Categories</a>
                <a class="admin-link" href="<?= url('admin/places') ?>"><i class="bi bi-shop me-2"></i>Places</a>
                <a class="admin-link" href="<?= url('admin/users') ?>"><i class="bi bi-people me-2"></i>Users</a>
                <a class="admin-link" href="<?= url('admin/reviews') ?>"><i class="bi bi-flag me-2"></i>Reviews & Reports</a>
                <a class="admin-link" href="<?= url('') ?>"><i class="bi bi-arrow-left-circle me-2"></i>Back to site</a>
            </div>
        </aside>
        <main class="col-lg-10 p-4 p-lg-5">
            <?php require base_path('views/components/alert.php'); ?>
            <?php require $contentView; ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
