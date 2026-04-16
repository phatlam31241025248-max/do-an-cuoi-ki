<?php $notificationService = class_exists('Services\NotificationService') ? new Services\NotificationService() : null; ?>
<nav class="navbar navbar-expand-lg sticky-top glass-nav border-bottom">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('') ?>">
            <span class="brand-mark"><i class="bi bi-geo-alt-fill"></i></span>
            <div>
                <div class="brand-text">FoodSpace</div>
                <small class="brand-tagline">Khám phá món ngon, chia sẻ trải nghiệm thật</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <form class="d-flex mx-lg-4 flex-grow-1" action="<?= url('places') ?>" method="get">
                <div class="search-shell w-100">
                    <i class="bi bi-search"></i>
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm quán ăn, cà phê, món ngon...">
                </div>
            </form>
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="<?= url('') ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('places') ?>">Places</a></li>
                <?php if (is_logged_in()): ?>
                    <li class="nav-item"><a class="nav-link nav-cta-review" href="<?= url('review-studio') ?>"><i class="bi bi-pencil-square me-1"></i>Write Review</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('feed/following') ?>">Following Feed</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= url('notifications') ?>">
                            <i class="bi bi-bell"></i>
                            <?php $unread = $notificationService ? $notificationService->unreadCount((int) current_user()['id']) : 0; ?>
                            <?php if ($unread > 0): ?>
                                <span class="badge rounded-pill text-bg-danger notification-badge"><?= $unread ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (has_role('admin')): ?>
                        <li class="nav-item"><a class="nav-link nav-admin-link" href="<?= url('admin/dashboard') ?>">Admin Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <img src="<?= asset(current_user()['avatar'] ?: config('app.default_avatar')) ?>" class="avatar avatar-xs" alt="avatar">
                            <span><?= e(current_user()['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-soft border-0">
                            <li><a class="dropdown-item" href="<?= url('profile/' . current_user()['username']) ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('review-studio') ?>">Write Review</a></li>
                            <li><a class="dropdown-item" href="<?= url('my-reviews') ?>">My Reviews</a></li>
                            <li><a class="dropdown-item" href="<?= url('my-bookmarks') ?>">My Bookmarks</a></li>
                            <li><a class="dropdown-item" href="<?= url('collections') ?>">My Collections</a></li>
                            <li><a class="dropdown-item" href="<?= url('profile/edit') ?>">Edit Profile</a></li>
                            <?php if (has_role('admin')): ?>
                                <li><a class="dropdown-item" href="<?= url('admin/dashboard') ?>">Open Admin Workspace</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= url('logout') ?>" method="post" class="px-3">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-danger btn-sm w-100">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-primary rounded-pill px-3" href="<?= url('login') ?>">Đăng nhập</a></li>
                    <li class="nav-item"><a class="btn btn-primary rounded-pill px-3" href="<?= url('register') ?>">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
