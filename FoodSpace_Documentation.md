# FoodSpace — Full Project Documentation

## 1) Tóm tắt kiến trúc hệ thống
FoodSpace là website social review platform cho địa điểm ăn uống, thiết kế theo kiến trúc **PHP thuần OOP + MVC + Service Layer**.

### Kiến trúc lớp
- **public/index.php**: front controller, entry point duy nhất.
- **routes/web.php**: khai báo toàn bộ route public/user/admin/AJAX.
- **controllers/**: nhận request, gọi service, render view hoặc trả JSON.
- **services/**: chứa business logic, validate, kiểm tra quyền, phối hợp model.
- **models/**: truy cập DB với PDO, query dữ liệu và quan hệ.
- **views/**: Bootstrap 5 UI theo phong cách social modern.
- **helpers/**: Auth, Session, CSRF, Flash, Pagination, Validator, slug helpers.
- **middlewares/**: AuthMiddleware, GuestMiddleware, RoleMiddleware.
- **database/sql/foodspace.sql**: create database, create tables, indexes, constraints, seed data.

### Luồng request chuẩn
1. Apache rewrite về `public/index.php`
2. Router đọc URI và method
3. Middleware kiểm tra đăng nhập / quyền
4. Controller gọi Service
5. Service validate + thao tác Model
6. Controller render view hoặc trả JSON
7. Frontend cập nhật UI bằng AJAX khi cần

### RBAC
- **Guest**: xem home, places, place detail, reviews, public profile.
- **User**: review, comment, like, bookmark, follow, collection, notifications, reports.
- **Admin**: dashboard, categories CRUD, places CRUD, users, roles, reports, moderation.

---

## 2) Cấu trúc thư mục project
```text
FoodSpace/
├── bootstrap.php
├── config/
│   ├── app.php
│   └── database.php
├── controllers/
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── PlaceController.php
│   ├── ReviewController.php
│   ├── CommentController.php
│   ├── BookmarkController.php
│   ├── CollectionController.php
│   ├── FollowController.php
│   ├── NotificationController.php
│   ├── ProfileController.php
│   ├── ReportController.php
│   ├── AdminController.php
│   ├── AdminCategoryController.php
│   ├── AdminPlaceController.php
│   ├── AdminUserController.php
│   └── AdminReviewController.php
├── core/
│   ├── Database.php
│   ├── BaseModel.php
│   ├── BaseController.php
│   ├── Request.php
│   ├── Response.php
│   └── Router.php
├── helpers/
│   ├── functions.php
│   ├── Session.php
│   ├── Auth.php
│   ├── Validator.php
│   ├── Csrf.php
│   ├── Flash.php
│   ├── Pagination.php
│   └── Str.php
├── middlewares/
│   ├── AuthMiddleware.php
│   ├── GuestMiddleware.php
│   └── RoleMiddleware.php
├── models/
│   ├── UserModel.php
│   ├── RoleModel.php
│   ├── UserRoleModel.php
│   ├── CategoryModel.php
│   ├── PlaceModel.php
│   ├── ReviewModel.php
│   ├── CommentModel.php
│   ├── ReviewLikeModel.php
│   ├── ReviewReportModel.php
│   ├── PlaceBookmarkModel.php
│   ├── CollectionModel.php
│   ├── CollectionPlaceModel.php
│   ├── UserFollowModel.php
│   └── NotificationModel.php
├── services/
│   ├── AuthService.php
│   ├── UserService.php
│   ├── RoleService.php
│   ├── PlaceService.php
│   ├── ReviewService.php
│   ├── CommentService.php
│   ├── BookmarkService.php
│   ├── CollectionService.php
│   ├── FollowService.php
│   ├── NotificationService.php
│   ├── AdminService.php
│   └── ReportService.php
├── routes/
│   └── web.php
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── assets/
│   │   ├── css/app.css
│   │   ├── js/app.js
│   │   └── images/*.svg
│   └── uploads/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── home/
│   ├── auth/
│   ├── places/
│   ├── profile/
│   ├── user/
│   ├── notifications/
│   └── admin/
├── database/
│   ├── sql/foodspace.sql
│   └── seeds/
└── storage/logs/
```

### Vai trò từng thư mục
- **config/**: cấu hình app + DB.
- **controllers/**: lớp điều hướng request.
- **models/**: truy vấn DB.
- **services/**: nghiệp vụ chính.
- **views/**: HTML/PHP templates.
- **middlewares/**: chặn truy cập theo auth/role.
- **helpers/**: utility chung.
- **public/**: document root, assets, uploads.
- **database/**: schema + seed import phpMyAdmin.
- **core/**: hạ tầng MVC mini framework.
- **storage/logs/**: nơi mở rộng logging sau này.

---

## 3) Thiết kế CSDL + ERD mô tả

### Bảng chính
- `users`
- `roles`
- `user_roles`
- `categories`
- `places`
- `reviews`
- `comments`
- `review_likes`
- `review_reports`
- `place_bookmarks`
- `collections`
- `collection_places`
- `user_follows`
- `notifications`

### Quan hệ ERD
- User **1-n** Review
- User **1-n** Comment
- User **n-n** Role qua `user_roles`
- Category **1-n** Place
- Place **1-n** Review
- Review **1-n** Comment
- User **n-n** Review qua `review_likes`
- User **1-n** Collection
- Collection **n-n** Place qua `collection_places`
- User **n-n** User qua `user_follows`
- User **1-n** Notification
- Review **1-n** ReviewReport
- User **n-n** Place qua `place_bookmarks`

### ERD mô tả văn bản
- `places.category_id -> categories.id`
- `places.created_by -> users.id`
- `reviews.user_id -> users.id`
- `reviews.place_id -> places.id`
- `comments.review_id -> reviews.id`
- `comments.user_id -> users.id`
- `review_likes.user_id -> users.id`
- `review_likes.review_id -> reviews.id`
- `review_reports.user_id -> users.id`
- `review_reports.review_id -> reviews.id`
- `place_bookmarks.user_id -> users.id`
- `place_bookmarks.place_id -> places.id`
- `collections.user_id -> users.id`
- `collection_places.collection_id -> collections.id`
- `collection_places.place_id -> places.id`
- `user_follows.follower_id -> users.id`
- `user_follows.following_id -> users.id`
- `notifications.user_id -> users.id`
- `notifications.actor_id -> users.id`

---

## 4) SQL đầy đủ để import phpMyAdmin
Toàn bộ SQL đã nằm trong file:
- `database/sql/foodspace.sql`

### Nội dung có sẵn
- `DROP DATABASE IF EXISTS`
- `CREATE DATABASE foodspace_db`
- `USE foodspace_db`
- `CREATE TABLE` đầy đủ cho 14 bảng
- `PRIMARY KEY`, `FOREIGN KEY`, `UNIQUE KEY`, `INDEX`
- `ENUM`, `CHECK`, `DEFAULT`
- Seed data đầy đủ demo
- Recalculate `avg_rating`, `review_count`, `helpful_count`, `report_count`, `rank_score`

---

## 5) Danh sách Models + thuộc tính + quan hệ

### UserModel
Thuộc tính: `id, full_name, username, email, password_hash, avatar, bio, status, created_at, updated_at`

Methods chính:
- `findByEmailOrUsername()`
- `findByUsername()`
- `getProfileByUsername()`
- `getTopReviewers()`
- `paginateForAdmin()`

### RoleModel
Thuộc tính: `id, name`

### UserRoleModel
Thuộc tính: `user_id, role_id`

Methods:
- `getRoleNamesForUser()`
- `userHasRole()`
- `assignRole()`
- `syncRoles()`

### CategoryModel
Thuộc tính: `id, name, slug, description, created_at, updated_at`

### PlaceModel
Thuộc tính: `id, category_id, name, slug, address, description, thumbnail, cover_image, phone, open_hours, price_range, avg_rating, review_count, created_by, created_at, updated_at`

Methods:
- `search()`
- `findBySlug()`
- `getHotPlaces()`
- `recalculateStats()`
- `allWithCategory()`

### ReviewModel
Thuộc tính: `id, user_id, place_id, rating, title, content, image, verified_score, rank_score, helpful_count, report_count, status, created_at, updated_at`

Methods:
- `getHomeFeed()`
- `getByPlace()`
- `getByUser()`
- `getFollowingFeed()`
- `findDetailed()`
- `updateRankScore()`
- `forAdmin()`

### CommentModel
Thuộc tính: `id, review_id, user_id, content, created_at, updated_at`

### ReviewLikeModel
Thuộc tính: `user_id, review_id, created_at`

### ReviewReportModel
Thuộc tính: `id, review_id, user_id, reason, created_at`

### PlaceBookmarkModel
Thuộc tính: `user_id, place_id, created_at`

### CollectionModel
Thuộc tính: `id, user_id, name, description, privacy, created_at, updated_at`

### CollectionPlaceModel
Thuộc tính: `collection_id, place_id, created_at`

### UserFollowModel
Thuộc tính: `follower_id, following_id, created_at`

### NotificationModel
Thuộc tính: `id, user_id, actor_id, type, reference_id, message, is_read, created_at`

---

## 6) Danh sách Services + chức năng

### AuthService
- `register()`
- `login()`
- `logout()`
- password_hash / password_verify
- gán role `user` mặc định

### UserService
- `getProfile()`
- `updateProfile()`
- `getTopReviewers()`
- `listForAdmin()`
- `updateStatus()`

### RoleService
- `userHasRole()`
- `assignRoleByName()`
- `syncRoles()`

### PlaceService
- `searchPlaces()`
- `getPlaceDetail()`
- `getCategories()`
- `getHotPlaces()`
- `save()`
- `delete()`
- `listForAdmin()`

### ReviewService
- `homeFeed()`
- `followingFeed()`
- `create()`
- `update()`
- `delete()`
- `hide()`
- `toggleLike()`
- `listForAdmin()`

### CommentService
- `add()`
- `getByReview()`
- tạo notification cho chủ review

### BookmarkService
- `toggle()`
- `getUserBookmarks()`

### CollectionService
- `getUserCollections()`
- `featuredCollections()`
- `create()`
- `update()`
- `delete()`
- `togglePlace()`

### FollowService
- `toggle()`
- `isFollowing()`
- chặn follow chính mình
- tạo notification khi follow

### NotificationService
- `create()`
- `getByUser()`
- `unreadCount()`
- `markAsRead()`
- `markAllAsRead()`

### AdminService
- `dashboard()` thống kê users/places/reviews/reports/categories

### ReportService
- `reportReview()`
- `listReports()`
- chặn report trùng người dùng
- tăng `report_count`, cập nhật `rank_score`

---

## 7) Route map đầy đủ

### Public routes
- `GET /`
- `GET /login`
- `POST /login`
- `GET /register`
- `POST /register`
- `GET /places`
- `GET /places/{slug}`
- `GET /profile/{username}`

### User routes
- `POST /logout`
- `GET /profile/edit`
- `POST /profile/update`
- `GET /my-bookmarks`
- `GET /feed/following`
- `GET /my-reviews`
- `GET /collections`
- `POST /collections/store`
- `POST /collections/{id}/update`
- `POST /collections/{id}/delete`
- `GET /notifications`

### Review routes
- `POST /reviews/store`
- `POST /reviews/{id}/update`
- `POST /reviews/{id}/delete`
- `POST /reviews/{id}/like`
- `POST /reviews/{id}/comment`
- `POST /reviews/{id}/report`

### Social action routes
- `POST /places/{id}/bookmark`
- `POST /users/{id}/follow`
- `POST /collections/{id}/toggle-place`
- `POST /notifications/read`
- `POST /notifications/read-all`

### Admin routes
- `GET /admin/dashboard`
- `GET /admin/categories`
- `POST /admin/categories/store`
- `POST /admin/categories/{id}/update`
- `POST /admin/categories/{id}/delete`
- `GET /admin/places`
- `POST /admin/places/store`
- `POST /admin/places/{id}/update`
- `POST /admin/places/{id}/delete`
- `GET /admin/users`
- `POST /admin/users/{id}/role`
- `POST /admin/users/{id}/status`
- `GET /admin/reviews`
- `POST /admin/reviews/{id}/hide`
- `POST /admin/reviews/{id}/delete`

---

## 8) Controllers đầy đủ
- `HomeController`
- `AuthController`
- `PlaceController`
- `ReviewController`
- `CommentController`
- `BookmarkController`
- `CollectionController`
- `FollowController`
- `NotificationController`
- `ProfileController`
- `ReportController`
- `AdminController`
- `AdminCategoryController`
- `AdminPlaceController`
- `AdminUserController`
- `AdminReviewController`

### Nghiệp vụ đã hiện thực đúng yêu cầu
1. **PLACES:** Search đơn giản theo tên/địa chỉ/category + sort latest/rating/popular
2. **REVIEWS:** Tạo review + cập nhật `avg_rating` / `review_count`
3. **COMMENTS:** Thêm comment bằng AJAX
4. **LIKES:** Toggle like review + realtime helpful_count
5. **FOLLOWS:** Toggle follow/unfollow + chặn self-follow
6. **BOOKMARKS:** Toggle bookmark place
7. **COLLECTIONS:** Toggle place trong collection, chỉ chủ collection được sửa
8. **NOTIFICATIONS:** List, mark read, mark all read
9. **REPORTS:** Report review + tăng `report_count`
10. **ROLES:** Check role qua `Auth::hasRole()` và `RoleMiddleware`

---

## 9) Views cần tạo

### Layouts
- `views/layouts/main.php`
- `views/layouts/admin.php`

### Components
- `views/components/navbar.php`
- `views/components/footer.php`
- `views/components/alert.php`
- `views/components/place-card.php`
- `views/components/review-card.php`

### Pages
- `views/home/index.php`
- `views/auth/login.php`
- `views/auth/register.php`
- `views/places/index.php`
- `views/places/show.php`
- `views/profile/show.php`
- `views/profile/edit.php`
- `views/user/bookmarks.php`
- `views/user/collections.php`
- `views/user/feed.php`
- `views/notifications/index.php`
- `views/admin/dashboard.php`
- `views/admin/categories/index.php`
- `views/admin/places/index.php`
- `views/admin/users/index.php`
- `views/admin/reviews/index.php`

---

## 10) Source code đầy đủ cho các file chính
Mã nguồn đầy đủ đã được tạo trong project `FoodSpace/`.

### File nền tảng quan trọng nhất
- `public/index.php`
- `routes/web.php`
- `core/Router.php`
- `core/Database.php`
- `core/BaseModel.php`
- `core/BaseController.php`
- `helpers/Auth.php`
- `helpers/Csrf.php`
- `middlewares/AuthMiddleware.php`
- `middlewares/RoleMiddleware.php`
- `services/*.php`
- `controllers/*.php`
- `views/**/*.php`
- `public/assets/css/app.css`
- `public/assets/js/app.js`
- `database/sql/foodspace.sql`

### Ghi chú đồng bộ source
- Route khớp với controller methods
- Controller khớp với service methods
- Service khớp với model queries
- View đã dùng Bootstrap 5 + Bootstrap Icons
- AJAX endpoints trả JSON theo chuẩn thống nhất
- SQL seed khớp tài khoản demo và dữ liệu giao diện

---

## 11) AJAX endpoints + JSON response mẫu

### Endpoints AJAX đã hỗ trợ
- `POST /reviews/{id}/like`
- `POST /reviews/{id}/comment`
- `POST /reviews/{id}/report`
- `POST /places/{id}/bookmark`
- `POST /users/{id}/follow`
- `POST /collections/{id}/toggle-place`
- `POST /notifications/read`
- `POST /notifications/read-all`

### JSON success mẫu
```json
{
  "status": "success",
  "message": "Thao tác thành công",
  "data": {
    "liked": true,
    "helpful_count": 12
  }
}
```

### JSON error mẫu
```json
{
  "status": "error",
  "message": "Không thể thực hiện thao tác",
  "data": {
    "errors": {
      "reason": ["Trường này là bắt buộc."]
    }
  }
}
```

### Frontend AJAX
`public/assets/js/app.js` đang xử lý:
- Like realtime
- Follow realtime
- Bookmark realtime
- Add/remove collection realtime
- Report review bằng prompt + JSON
- Mark read notification
- Comment review không reload trang

---

## 12) Giao diện Bootstrap hoàn chỉnh

### Trang chủ
- Sticky navbar
- Hero section hiện đại
- Social feed ở giữa
- Sidebar trái: categories + quick links
- Sidebar phải: top reviewers, hot places, featured collections
- Review card có avatar, username, place, rating, content, actions

### Places index
- Search + filter + sort
- Grid cards đẹp
- Pagination
- Empty state đẹp

### Place detail
- Cover hero lớn
- Summary card hiển thị rating + review_count
- Bookmark / add to collection
- Review list
- Form viết review

### Profile
- Banner profile
- Avatar lớn
- Follow/unfollow
- Stats followers/following/reviews
- Reviews + collections

### Notifications
- List đẹp
- Badge unread
- Mark read / mark all

### Admin
- Sidebar admin riêng
- Dashboard cards thống kê
- Management tables cho categories / places / users / reviews-reports

---

## 13) Seed data mẫu
Seed đã có trong `database/sql/foodspace.sql`:
- 2 roles: `admin`, `user`
- 1 admin account
- 6 user demo
- 5 categories
- 12 places
- 24 reviews
- 24 comments
- nhiều likes
- nhiều bookmarks
- 6 collections + collection_places
- nhiều follow relations
- 12 notifications
- 4 reports

---

## 14) Hướng dẫn chạy project local bằng XAMPP/WAMP

### Cách 1: chạy trực tiếp trong XAMPP
1. Chép thư mục `FoodSpace` vào `htdocs/`
2. Mở XAMPP, start **Apache** + **MySQL**
3. Mở `phpMyAdmin`
4. Import file `FoodSpace/database/sql/foodspace.sql`
5. Kiểm tra `config/database.php`
   - host: `127.0.0.1`
   - port: `3306`
   - database: `foodspace_db`
   - username: `root`
   - password: ``
6. Kiểm tra `config/app.php`
   - `base_url = http://localhost/FoodSpace/public`
7. Truy cập:
   - `http://localhost/FoodSpace/public`

### Cách 2: nếu đổi tên thư mục project
Chỉ cần sửa lại `config/app.php`:
```php
'base_url' => 'http://localhost/TEN_THU_MUC/public',
```

### Cấu hình Apache
Project đã có `.htaccess` tại `public/.htaccess`, nên chỉ cần bật `mod_rewrite` (thường XAMPP có sẵn).

---

## 15) Tài khoản demo admin/user
- **Admin**
  - Email: `admin@foodspace.local`
  - Password: `admin123`
- **User demo**
  - Email: `mai@foodspace.local`
  - Password: `user123`

Ngoài ra còn có các user demo khác:
- `khanh@foodspace.local`
- `linh@foodspace.local`
- `duy@foodspace.local`
- `an@foodspace.local`
- `phuc@foodspace.local`

Password chung cho user demo: `user123`

---

## 16) Gợi ý nâng cấp sau này
1. Upload ảnh thật bằng `move_uploaded_file()` + validate MIME/size
2. Phân trang AJAX cho comments và notifications
3. Tách `api.php` riêng cho AJAX endpoints
4. Thêm bảng `place_images`
5. Thêm review draft / moderation queue
6. Tạo hệ thống `soft delete`
7. Thêm search gợi ý realtime
8. Thêm leaderboard reviewer theo tuần/tháng
9. Thêm OAuth login (Google/Facebook)
10. Thêm websocket / SSE cho notifications realtime hơn
11. Thêm AI summary cho review/place
12. Tối ưu image lazy loading + caching
13. Thêm unit test cho services
14. Tách config `.env`
15. Chuyển sang REST API + frontend SPA khi cần scale

---

## 17) Ghi chú thực tế
Đây là một **MVP rất mạnh cho đồ án môn học**: có đủ kiến trúc, có dữ liệu demo, có RBAC, có AJAX, có admin panel, có UI social hiện đại và đủ thuyết trình về OOP + MVC + Service Layer.

Khi demo, nên trình bày theo flow:
1. Guest xem home + places
2. Login user
3. Review / like / comment / bookmark / follow / collection
4. Xem notifications
5. Login admin
6. Quản lý category / place / user / review / report

