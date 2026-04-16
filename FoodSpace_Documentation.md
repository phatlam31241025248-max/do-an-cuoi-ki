# FoodSpace — Full Project Documentation

## 1. Tổng quan hệ thống

FoodSpace là website mạng xã hội review địa điểm ăn uống, được xây dựng theo kiến trúc **PHP thuần OOP + MVC + Service Layer**. Hệ thống cho phép người dùng khám phá địa điểm ăn uống, chia sẻ trải nghiệm thực tế, tương tác với cộng đồng thông qua review, bình luận, like, bookmark, follow, collection và notification.

Dự án được phát triển với mục tiêu tạo ra một nền tảng social review hiện đại, có cấu trúc rõ ràng, dễ mở rộng và phù hợp với đồ án môn học / đồ án cuối kỳ.

---

## 2. Kiến trúc hệ thống

### 2.1. Mô hình tổng thể
FoodSpace được tổ chức theo các lớp chính:

- **Front Controller**: tiếp nhận toàn bộ request từ người dùng
- **Router**: phân tích URI, HTTP method và điều hướng tới controller phù hợp
- **Controller**: tiếp nhận request, gọi service, render view hoặc trả JSON
- **Service Layer**: xử lý nghiệp vụ chính, validate dữ liệu, kiểm tra quyền, phối hợp model
- **Model**: thao tác dữ liệu với cơ sở dữ liệu MySQL thông qua PDO
- **View**: hiển thị giao diện bằng PHP + Bootstrap 5
- **Helpers / Middlewares**: hỗ trợ auth, csrf, flash, validation, role check, pagination

### 2.2. Luồng xử lý cơ bản
1. Request được chuyển tới entry point của ứng dụng
2. Router đọc URI và method
3. Middleware kiểm tra đăng nhập hoặc quyền truy cập nếu cần
4. Controller nhận request
5. Service xử lý nghiệp vụ
6. Model thao tác dữ liệu
7. Controller render HTML hoặc trả JSON cho AJAX
8. Frontend cập nhật giao diện tương ứng

### 2.3. Mô hình phân quyền RBAC
Hệ thống áp dụng cơ chế **Role-Based Access Control** gồm 3 vai trò:

- **Guest**: xem trang chủ, danh sách địa điểm, chi tiết địa điểm, review
- **User**: review, comment, like, bookmark, collection, follow, notifications, reports
- **Admin**: quản trị category, place, user, review, report và moderation

---

## 3. Công nghệ sử dụng

- **Backend:** PHP 8.2+
- **Kiến trúc:** OOP + MVC + Service Layer
- **Database:** MySQL / MariaDB
- **Truy cập dữ liệu:** PDO + Prepared Statements
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Tương tác động:** AJAX (Fetch API) + JSON
- **Môi trường local:** XAMPP / WAMP / Laragon
- **Môi trường demo:** hosting hỗ trợ PHP và MySQL

---

## 4. Cấu trúc thư mục dự án

```text
FoodSpace/
├── bootstrap.php
├── index.php
├── .htaccess
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
│   ├── Str.php
│   └── Upload.php
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
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/
│   ├── places/
│   └── reviews/
├── database/
│   ├── sql/foodspace.sql
│   └── seeds/
└── storage/
    └── logs/
