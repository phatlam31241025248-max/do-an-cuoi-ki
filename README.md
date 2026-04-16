# FoodSpace

FoodSpace là đồ án website mạng xã hội review địa điểm ăn uống, được xây dựng theo kiến trúc **PHP thuần OOP + MVC + Service Layer**, sử dụng **MySQL**, **Bootstrap 5** và **AJAX + JSON**.

Dự án được phát triển với mục tiêu mô phỏng một nền tảng social review hiện đại, nơi người dùng có thể khám phá địa điểm ăn uống, viết review, bình luận, like, bookmark, follow người dùng khác và nhận thông báo tương tác.

---

## Công nghệ sử dụng

- PHP 8.2+
- MySQL / MariaDB
- Apache (XAMPP / WAMP / Laragon / Hosting hỗ trợ PHP)
- PDO + Prepared Statements
- Bootstrap 5 + Bootstrap Icons
- AJAX (Fetch API) + JSON

---

## Kiến trúc hệ thống

FoodSpace được tổ chức theo mô hình:

- **MVC**: tách rõ Model - View - Controller
- **Service Layer**: xử lý nghiệp vụ riêng khỏi controller
- **Helpers / Middlewares**: hỗ trợ auth, CSRF, validation, flash message, role check
- **AJAX endpoints**: phục vụ các chức năng tương tác như like, comment, bookmark, follow, report, notifications

---

## Chức năng chính

### Guest
- Xem trang chủ
- Xem danh sách địa điểm
- Tìm kiếm và lọc địa điểm
- Xem chi tiết địa điểm
- Xem review và xếp hạng

### User
- Đăng ký / đăng nhập / đăng xuất
- Viết, sửa, xóa review
- Bình luận review
- Like review
- Bookmark địa điểm
- Tạo collection
- Thêm / xóa địa điểm trong collection
- Follow / unfollow người dùng
- Xem following feed
- Nhận notifications
- Report review

### Admin
- Quản lý category
- Quản lý place
- Quản lý user
- Phân quyền user / admin
- Quản lý review và report
- Ẩn / xóa nội dung vi phạm

---

## Cấu trúc thư mục chính

```text
FoodSpace/
├── bootstrap.php
├── index.php
├── .htaccess
├── config/
├── controllers/
├── core/
├── helpers/
├── middlewares/
├── models/
├── routes/
├── services/
├── views/
├── assets/
├── uploads/
├── database/
└── storage/
