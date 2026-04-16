# FoodSpace

FoodSpace là đồ án website mạng xã hội review địa điểm ăn uống được triển khai theo **PHP thuần OOP + MVC + Service Layer**, **MySQL**, **Bootstrap 5**, **AJAX + JSON**, phù hợp chạy trên **Apache / XAMPP / WAMP**.

## Stack
- PHP 8.2+
- MySQL 8+
- Apache (XAMPP/WAMP)
- PDO + Prepared Statements
- Bootstrap 5 + Bootstrap Icons
- AJAX (Fetch API) + JSON

## Branding
- **Logo text style:** FoodSpace với icon ghim bản đồ màu cam đỏ và chữ đậm bo mềm.
- **Tagline:** _Khám phá món ngon, chia sẻ trải nghiệm thật._
- **Bảng màu:** cam đỏ `#ff5a3d`, tím accent `#7c3aed`, nền sáng `#f6f8fb`, chữ `#0f172a`.
- **UI style:** social feed hiện đại, bo góc lớn, shadow nhẹ, spacing thoáng, card-based layout.

## Import database
Import file: `database/sql/foodspace.sql`

## Demo accounts
- Admin: `admin@foodspace.local` / `admin123`
- User: `mai@foodspace.local` / `user123`

## Chạy nhanh
1. Chép thư mục `FoodSpace` vào `htdocs`.
2. Import `database/sql/foodspace.sql` bằng phpMyAdmin.
3. Nếu cần, sửa `config/database.php` và `config/app.php`.
4. Truy cập `http://localhost/FoodSpace/public`.

## Tài liệu đầy đủ
Xem: `FoodSpace_Documentation.md`

## Upload image
- Ảnh review và ảnh địa điểm được tải trực tiếp lên `public/uploads/reviews` và `public/uploads/places`.
- Nếu dùng XAMPP/WAMP, hãy bảo đảm thư mục `public/uploads` có quyền ghi.
