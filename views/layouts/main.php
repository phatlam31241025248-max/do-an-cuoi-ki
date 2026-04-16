<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(Helpers\Csrf::token()) ?>">
    <title><?= e(($title ?? 'FoodSpace') . ' | FoodSpace') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= asset('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="foodspace-body">
<?php require base_path('views/components/navbar.php'); ?>
<main class="container py-4 py-lg-5">
    <?php require base_path('views/components/alert.php'); ?>
    <?php require $contentView; ?>
</main>
<?php require base_path('views/components/footer.php'); ?>
<script>window.FOODSPACE_BASE_URL = <?= json_encode(config('app.base_url')) ?>;</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
