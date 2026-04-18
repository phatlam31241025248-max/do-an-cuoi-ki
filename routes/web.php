<?php

$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [AuthController::class, 'showLogin'], [Middlewares\GuestMiddleware::class]);
$router->post('/login', [AuthController::class, 'login'], [Middlewares\GuestMiddleware::class]);
$router->get('/register', [AuthController::class, 'showRegister'], [Middlewares\GuestMiddleware::class]);
$router->post('/register', [AuthController::class, 'register'], [Middlewares\GuestMiddleware::class]);
$router->post('/logout', [AuthController::class, 'logout'], [Middlewares\AuthMiddleware::class]);

$router->get('/places', [PlaceController::class, 'index']);
$router->get('/places/{slug}', [PlaceController::class, 'show']);

$router->get('/review-studio', [ReviewController::class, 'createPage'], [Middlewares\AuthMiddleware::class]);
$router->post('/review-studio/store', [ReviewController::class, 'storeStudio'], [Middlewares\AuthMiddleware::class]);

$router->get('/profile/edit', [ProfileController::class, 'edit'], [Middlewares\AuthMiddleware::class]);
$router->post('/profile/update', [ProfileController::class, 'update'], [Middlewares\AuthMiddleware::class]);
$router->get('/profile/{username}', [ProfileController::class, 'show']);
$router->get('/my-bookmarks', [ProfileController::class, 'bookmarks'], [Middlewares\AuthMiddleware::class]);
$router->get('/feed/following', [ProfileController::class, 'feed'], [Middlewares\AuthMiddleware::class]);
$router->get('/my-reviews', [ProfileController::class, 'myReviews'], [Middlewares\AuthMiddleware::class]);

$router->post('/reviews/store', [ReviewController::class, 'store'], [Middlewares\AuthMiddleware::class]);
$router->post('/reviews/{id}/update', [ReviewController::class, 'update'], [Middlewares\AuthMiddleware::class]);
$router->post('/reviews/{id}/delete', [ReviewController::class, 'destroy'], [Middlewares\AuthMiddleware::class]);
$router->post('/reviews/{id}/like', [ReviewController::class, 'like'], [Middlewares\AuthMiddleware::class]);
$router->post('/reviews/{id}/comment', [CommentController::class, 'store'], [Middlewares\AuthMiddleware::class]);
$router->post('/reviews/{id}/report', [ReportController::class, 'store'], [Middlewares\AuthMiddleware::class]);

$router->post('/places/{id}/bookmark', [BookmarkController::class, 'toggle'], [Middlewares\AuthMiddleware::class]);
$router->post('/users/{id}/follow', [FollowController::class, 'toggle'], [Middlewares\AuthMiddleware::class]);

$router->get('/collections', [CollectionController::class, 'index'], [Middlewares\AuthMiddleware::class]);
$router->post('/collections/store', [CollectionController::class, 'store'], [Middlewares\AuthMiddleware::class]);
$router->post('/collections/{id}/update', [CollectionController::class, 'update'], [Middlewares\AuthMiddleware::class]);
$router->post('/collections/{id}/delete', [CollectionController::class, 'destroy'], [Middlewares\AuthMiddleware::class]);
$router->post('/collections/{id}/toggle-place', [CollectionController::class, 'togglePlace'], [Middlewares\AuthMiddleware::class]);

$router->get('/notifications', [NotificationController::class, 'index'], [Middlewares\AuthMiddleware::class]);
$router->post('/notifications/read', [NotificationController::class, 'read'], [Middlewares\AuthMiddleware::class]);
$router->post('/notifications/read-all', [NotificationController::class, 'readAll'], [Middlewares\AuthMiddleware::class]);

$router->get('/admin/dashboard', [AdminController::class, 'dashboard'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->get('/admin/categories', [AdminCategoryController::class, 'index'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/categories/store', [AdminCategoryController::class, 'store'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/categories/{id}/update', [AdminCategoryController::class, 'update'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/categories/{id}/delete', [AdminCategoryController::class, 'destroy'], [Middlewares\RoleMiddleware::class . ':admin']);

$router->get('/admin/places', [AdminPlaceController::class, 'index'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/places/store', [AdminPlaceController::class, 'store'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/places/{id}/update', [AdminPlaceController::class, 'update'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/places/{id}/delete', [AdminPlaceController::class, 'destroy'], [Middlewares\RoleMiddleware::class . ':admin']);

$router->get('/admin/users', [AdminUserController::class, 'index'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/users/{id}/role', [AdminUserController::class, 'updateRole'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/users/{id}/status', [AdminUserController::class, 'updateStatus'], [Middlewares\RoleMiddleware::class . ':admin']);

$router->get('/admin/reviews', [AdminReviewController::class, 'index'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/reviews/{id}/hide', [AdminReviewController::class, 'hide'], [Middlewares\RoleMiddleware::class . ':admin']);
$router->post('/admin/reviews/{id}/delete', [AdminReviewController::class, 'destroy'], [Middlewares\RoleMiddleware::class . ':admin']);
