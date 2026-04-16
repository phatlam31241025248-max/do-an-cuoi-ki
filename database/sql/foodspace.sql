DROP DATABASE IF EXISTS foodspace_db;
CREATE DATABASE foodspace_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foodspace_db;

SET NAMES utf8mb4;
SET time_zone = '+07:00';
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS user_follows;
DROP TABLE IF EXISTS collection_places;
DROP TABLE IF EXISTS collections;
DROP TABLE IF EXISTS place_bookmarks;
DROP TABLE IF EXISTS review_reports;
DROP TABLE IF EXISTS review_likes;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS places;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'assets/images/avatar-default.svg',
    bio VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'banned') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_users_username (username),
    UNIQUE KEY uk_users_email (email),
    KEY idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    UNIQUE KEY uk_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_roles (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE places (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL,
    address VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    thumbnail VARCHAR(255) DEFAULT 'assets/images/place-placeholder.svg',
    cover_image VARCHAR(255) DEFAULT 'assets/images/place-placeholder.svg',
    phone VARCHAR(30) DEFAULT NULL,
    open_hours VARCHAR(100) DEFAULT NULL,
    price_range VARCHAR(80) DEFAULT NULL,
    avg_rating DECIMAL(3,2) NOT NULL DEFAULT 0,
    review_count INT UNSIGNED NOT NULL DEFAULT 0,
    created_by INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_places_slug (slug),
    KEY idx_places_category (category_id),
    KEY idx_places_rating (avg_rating),
    KEY idx_places_review_count (review_count),
    CONSTRAINT fk_places_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_places_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    place_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    verified_score DECIMAL(5,2) NOT NULL DEFAULT 0,
    rank_score DECIMAL(7,2) NOT NULL DEFAULT 0,
    helpful_count INT UNSIGNED NOT NULL DEFAULT 0,
    report_count INT UNSIGNED NOT NULL DEFAULT 0,
    status ENUM('visible', 'hidden') NOT NULL DEFAULT 'visible',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (rating BETWEEN 1 AND 5),
    KEY idx_reviews_place (place_id),
    KEY idx_reviews_user (user_id),
    KEY idx_reviews_status (status),
    KEY idx_reviews_rank (rank_score),
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_place FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    review_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    content VARCHAR(400) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_comments_review (review_id),
    KEY idx_comments_user (user_id),
    CONSTRAINT fk_comments_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE review_likes (
    user_id INT UNSIGNED NOT NULL,
    review_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, review_id),
    KEY idx_review_likes_review (review_id),
    CONSTRAINT fk_review_likes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_review_likes_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE review_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    review_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    reason VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_review_report_once (review_id, user_id),
    KEY idx_review_reports_review (review_id),
    KEY idx_review_reports_user (user_id),
    CONSTRAINT fk_review_reports_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_review_reports_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE place_bookmarks (
    user_id INT UNSIGNED NOT NULL,
    place_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, place_id),
    KEY idx_place_bookmarks_place (place_id),
    CONSTRAINT fk_place_bookmarks_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_place_bookmarks_place FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE collections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    privacy ENUM('public', 'private') NOT NULL DEFAULT 'public',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_collections_user (user_id),
    CONSTRAINT fk_collections_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE collection_places (
    collection_id INT UNSIGNED NOT NULL,
    place_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (collection_id, place_id),
    KEY idx_collection_places_place (place_id),
    CONSTRAINT fk_collection_places_collection FOREIGN KEY (collection_id) REFERENCES collections(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_collection_places_place FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_follows (
    follower_id INT UNSIGNED NOT NULL,
    following_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (follower_id, following_id),
    CHECK (follower_id <> following_id),
    KEY idx_user_follows_following (following_id),
    CONSTRAINT fk_user_follows_follower FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_user_follows_following FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    actor_id INT UNSIGNED DEFAULT NULL,
    type ENUM('like_review', 'comment_review', 'follow_user', 'report_status') NOT NULL,
    reference_id INT UNSIGNED DEFAULT NULL,
    message VARCHAR(255) NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_notifications_user_read (user_id, is_read),
    KEY idx_notifications_actor (actor_id),
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_notifications_actor FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO roles (id, name) VALUES
(1, 'admin'),
(2, 'user');

INSERT INTO users (id, full_name, username, email, password_hash, avatar, bio, status, created_at, updated_at) VALUES
(1, 'FoodSpace Admin', 'admin', 'admin@foodspace.local', '$2y$12$8TDw0c8Q3UwOIZI5mSJ7bOtPQVwN6rp4Wr5ii1EoqLCk/QMQ74ibC', 'assets/images/avatar-default.svg', 'Quản trị viên hệ thống FoodSpace.', 'active', '2026-03-01 08:00:00', '2026-03-01 08:00:00'),
(2, 'Mai Trần', 'maitran', 'mai@foodspace.local', '$2y$12$tCOx8Hv.owVaQTqaMAyTROkb2ZxVxa0lBZxpHoEo3vViW3XYQ8pcy', 'assets/images/avatar-default.svg', 'Nghiện cafe specialty và brunch cuối tuần.', 'active', '2026-03-01 09:00:00', '2026-03-01 09:00:00'),
(3, 'Khánh Phạm', 'khanhpham', 'khanh@foodspace.local', '$2y$12$tCOx8Hv.owVaQTqaMAyTROkb2ZxVxa0lBZxpHoEo3vViW3XYQ8pcy', 'assets/images/avatar-default.svg', 'Street food hunter tại Sài Gòn.', 'active', '2026-03-02 08:00:00', '2026-03-02 08:00:00'),
(4, 'Linh Hoàng', 'linhhoang', 'linh@foodspace.local', '$2y$12$tCOx8Hv.owVaQTqaMAyTROkb2ZxVxa0lBZxpHoEo3vViW3XYQ8pcy', 'assets/images/avatar-default.svg', 'Healthy eater, mê quán đẹp và vegan bowl.', 'active', '2026-03-02 10:00:00', '2026-03-02 10:00:00'),
(5, 'Duy Võ', 'duyvo', 'duy@foodspace.local', '$2y$12$tCOx8Hv.owVaQTqaMAyTROkb2ZxVxa0lBZxpHoEo3vViW3XYQ8pcy', 'assets/images/avatar-default.svg', 'Fine dining, pairing và trải nghiệm dịch vụ.', 'active', '2026-03-03 11:00:00', '2026-03-03 11:00:00'),
(6, 'An Nguyễn', 'annguyen', 'an@foodspace.local', '$2y$12$tCOx8Hv.owVaQTqaMAyTROkb2ZxVxa0lBZxpHoEo3vViW3XYQ8pcy', 'assets/images/avatar-default.svg', 'Dessert lover, tìm quán check-in đẹp.', 'active', '2026-03-04 09:30:00', '2026-03-04 09:30:00'),
(7, 'Phúc Lê', 'phucle', 'phuc@foodspace.local', '$2y$12$tCOx8Hv.owVaQTqaMAyTROkb2ZxVxa0lBZxpHoEo3vViW3XYQ8pcy', 'assets/images/avatar-default.svg', 'Ăn ngon phải đi kèm value for money.', 'active', '2026-03-04 14:20:00', '2026-03-04 14:20:00');

INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), (1, 2),
(2, 2), (3, 2), (4, 2), (5, 2), (6, 2), (7, 2);

INSERT INTO categories (id, name, slug, description, created_at, updated_at) VALUES
(1, 'Cafe & Brunch', 'cafe-brunch', 'Không gian cà phê, brunch, bakery và chill cuối tuần.', '2026-03-01 08:00:00', '2026-03-01 08:00:00'),
(2, 'Street Food', 'street-food', 'Ẩm thực đường phố, món nhanh, giá tốt.', '2026-03-01 08:00:00', '2026-03-01 08:00:00'),
(3, 'Fine Dining', 'fine-dining', 'Không gian sang trọng, món ăn tinh tế, dịch vụ cao cấp.', '2026-03-01 08:00:00', '2026-03-01 08:00:00'),
(4, 'Dessert & Bakery', 'dessert-bakery', 'Bánh ngọt, dessert bar và tiệm bánh artisan.', '2026-03-01 08:00:00', '2026-03-01 08:00:00'),
(5, 'Vegan & Healthy', 'vegan-healthy', 'Quán eat-clean, vegan, salad bowl và đồ uống lành mạnh.', '2026-03-01 08:00:00', '2026-03-01 08:00:00');

INSERT INTO places (id, category_id, name, slug, address, description, thumbnail, cover_image, phone, open_hours, price_range, avg_rating, review_count, created_by, created_at, updated_at) VALUES
(1, 1, 'Morning Dew Cafe', 'morning-dew-cafe', '12 Nguyễn Huệ, Quận 1, TP.HCM', 'Cafe phong cách hiện đại, nhiều góc ngồi làm việc và brunch nhẹ buổi sáng.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000001', '07:00 - 22:00', '60.000đ - 180.000đ', 0, 0, 1, '2026-03-01 08:00:00', '2026-03-01 08:00:00'),
(2, 2, 'Bánh Mì Góc Phố', 'banh-mi-goc-pho', '48 Lê Lợi, Quận 1, TP.HCM', 'Xe bánh mì giòn nóng, nhân đầy và nước sốt house-made.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000002', '06:00 - 12:00', '20.000đ - 45.000đ', 0, 0, 1, '2026-03-01 08:10:00', '2026-03-01 08:10:00'),
(3, 3, 'Cloud Nine Dining', 'cloud-nine-dining', '88 Võ Văn Kiệt, Quận 1, TP.HCM', 'Nhà hàng fine dining view sông, menu tasting theo mùa.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000003', '17:30 - 23:00', '850.000đ - 2.200.000đ', 0, 0, 1, '2026-03-01 08:20:00', '2026-03-01 08:20:00'),
(4, 4, 'Sugar Atelier', 'sugar-atelier', '15 Trần Não, TP. Thủ Đức', 'Dessert studio với entremet, croissant và signature tea.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000004', '09:00 - 21:30', '55.000đ - 160.000đ', 0, 0, 1, '2026-03-01 08:30:00', '2026-03-01 08:30:00'),
(5, 5, 'Green Bowl Kitchen', 'green-bowl-kitchen', '44 Xuân Thủy, TP. Thủ Đức', 'Vegan bowls, smoothie, cold-pressed juice với nguyên liệu tươi.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000005', '08:00 - 21:00', '70.000đ - 190.000đ', 0, 0, 1, '2026-03-01 08:40:00', '2026-03-01 08:40:00'),
(6, 1, 'Roast Lab Saigon', 'roast-lab-saigon', '26 Pasteur, Quận 3, TP.HCM', 'Specialty coffee, filter bar và khu làm việc yên tĩnh.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000006', '07:30 - 22:30', '55.000đ - 150.000đ', 0, 0, 1, '2026-03-01 08:50:00', '2026-03-01 08:50:00'),
(7, 2, 'Hủ Tiếu Đêm 1988', 'hu-tieu-dem-1988', '120 Nguyễn Thái Bình, Quận 1, TP.HCM', 'Hủ tiếu nước trong, topping nhiều, mở muộn tới khuya.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000007', '18:00 - 01:30', '45.000đ - 85.000đ', 0, 0, 1, '2026-03-01 09:00:00', '2026-03-01 09:00:00'),
(8, 3, 'Ember Steak House', 'ember-steak-house', '6 Nguyễn Bỉnh Khiêm, Quận 1, TP.HCM', 'Steak house sang trọng, nguyên liệu nhập khẩu và service chỉn chu.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000008', '17:00 - 23:00', '650.000đ - 1.800.000đ', 0, 0, 1, '2026-03-01 09:10:00', '2026-03-01 09:10:00'),
(9, 4, 'Butter Story Bakery', 'butter-story-bakery', '99 Hoàng Sa, Quận 3, TP.HCM', 'Tiệm bánh artisan nổi tiếng với cookie và sourdough.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000009', '08:00 - 20:00', '35.000đ - 120.000đ', 0, 0, 1, '2026-03-01 09:20:00', '2026-03-01 09:20:00'),
(10, 5, 'Raw Bliss Hub', 'raw-bliss-hub', '31 Nguyễn Cừ, Quận 5, TP.HCM', 'Không gian healthy, nhiều lựa chọn gluten-free và vegan dessert.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000010', '09:00 - 21:00', '80.000đ - 210.000đ', 0, 0, 1, '2026-03-01 09:30:00', '2026-03-01 09:30:00'),
(11, 1, 'Nắng Rooftop Coffee', 'nang-rooftop-coffee', '27 Tôn Thất Thiệp, Quận 1, TP.HCM', 'Rooftop view đẹp, hợp ngắm hoàng hôn và chụp ảnh.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000011', '16:00 - 23:30', '65.000đ - 170.000đ', 0, 0, 1, '2026-03-01 09:40:00', '2026-03-01 09:40:00'),
(12, 2, 'Cơm Tấm Ba Nhà', 'com-tam-ba-nha', '73 Phạm Viết Chánh, Bình Thạnh, TP.HCM', 'Cơm tấm sườn bì chả đậm vị, phục vụ nhanh và đông khách trưa.', 'assets/images/place-placeholder.svg', 'assets/images/place-placeholder.svg', '0280000012', '06:30 - 14:00', '35.000đ - 70.000đ', 0, 0, 1, '2026-03-01 09:50:00', '2026-03-01 09:50:00');

INSERT INTO reviews (id, user_id, place_id, rating, title, content, image, verified_score, rank_score, helpful_count, report_count, status, created_at, updated_at) VALUES
(1, 2, 1, 5, 'Brunch đẹp và đồ uống cân bằng', 'Không gian sáng, bàn ghế thoải mái để làm việc 2-3 tiếng. Món avocado toast ngon, cà phê sữa hạt vừa vị, staff niềm nở.', NULL, 68, 0, 0, 0, 'visible', '2026-03-10 08:00:00', '2026-03-10 08:00:00'),
(2, 3, 2, 4, 'Bánh mì giòn nóng, nhân nhiều', 'Ổ bánh mì đầy đặn, pate thơm, xíu mại đậm vị. Điểm trừ là giờ cao điểm phải chờ hơi lâu.', NULL, 60, 0, 0, 0, 'visible', '2026-03-10 08:20:00', '2026-03-10 08:20:00'),
(3, 5, 3, 5, 'Tasting menu đáng thử cho dịp đặc biệt', 'Set menu 7 món lên rất nhịp nhàng, plating đẹp và service gần như flawless. Món cá áp chảo và dessert cuối bữa rất ấn tượng.', NULL, 74, 0, 0, 0, 'visible', '2026-03-10 09:00:00', '2026-03-10 09:00:00'),
(4, 6, 4, 5, 'Quầy bánh nhỏ nhưng rất tinh tế', 'Croissant butter nhiều lớp, raspberry tart chua ngọt vừa phải. Không gian xinh, ngồi chụp hình rất ổn.', NULL, 67, 0, 0, 0, 'visible', '2026-03-10 09:30:00', '2026-03-10 09:30:00'),
(5, 4, 5, 4, 'Healthy bowl đủ chất, sốt ngon', 'Quinoa bowl đầy đặn, rau tươi, sốt mè rang thanh vị. Giá hơi cao nhưng chất lượng ổn.', NULL, 62, 0, 0, 0, 'visible', '2026-03-10 10:00:00', '2026-03-10 10:00:00'),
(6, 2, 6, 5, 'Specialty coffee rất ổn định', 'Flat white cân bằng, pour over Ethiopia thơm rõ nốt hoa. Không gian yên tĩnh phù hợp làm việc sâu.', NULL, 70, 0, 0, 0, 'visible', '2026-03-10 11:00:00', '2026-03-10 11:00:00'),
(7, 7, 7, 4, 'Ăn khuya ổn áp', 'Nước lèo trong, topping đầy đủ. Không phải quán quá wow nhưng rất đáng tin khi cần bữa tối muộn.', NULL, 55, 0, 0, 0, 'visible', '2026-03-10 12:00:00', '2026-03-10 12:00:00'),
(8, 5, 8, 5, 'Steak chuẩn medium rare', 'Thịt mềm, crust tốt, sauce đi kèm hợp. Không gian sang, staff hiểu món và tư vấn rượu ổn.', NULL, 73, 0, 0, 0, 'visible', '2026-03-10 13:00:00', '2026-03-10 13:00:00'),
(9, 6, 9, 4, 'Cookie ngon nhưng quán hơi nhỏ', 'Chocolate cookie mềm bên trong, mùi bơ thơm. Cuối tuần khá đông, cần đi sớm để có chỗ.', NULL, 58, 0, 0, 0, 'visible', '2026-03-10 14:00:00', '2026-03-10 14:00:00'),
(10, 4, 10, 5, 'Healthy nhưng không nhạt nhẽo', 'Raw cheesecake ăn khá lạ miệng, kombucha mát. Menu nhiều lựa chọn gluten-free nên rất tiện.', NULL, 69, 0, 0, 0, 'visible', '2026-03-10 15:00:00', '2026-03-10 15:00:00'),
(11, 3, 11, 4, 'View sunset đẹp, nước ổn', 'Rooftop thoáng, hợp đi nhóm bạn chiều tối. Đồ uống không quá xuất sắc nhưng trải nghiệm tổng thể tốt.', NULL, 57, 0, 0, 0, 'visible', '2026-03-10 16:00:00', '2026-03-10 16:00:00'),
(12, 7, 12, 5, 'Cơm tấm chuẩn vị, sườn ngon', 'Sườn nướng thơm, không khô, nước mắm vừa miệng. Món lên nhanh và giá hợp lý.', NULL, 63, 0, 0, 0, 'visible', '2026-03-10 16:30:00', '2026-03-10 16:30:00'),
(13, 3, 1, 4, 'Cafe đẹp nhưng cuối tuần đông', 'Menu brunch ổn, tuy nhiên sáng cuối tuần hơi ồn. Nếu đi làm việc nên ghé ngày thường.', NULL, 59, 0, 0, 0, 'visible', '2026-03-11 08:30:00', '2026-03-11 08:30:00'),
(14, 2, 2, 5, 'Giá mềm và đáng quay lại', 'Thịt nguội vừa vị, bánh giòn, rau dưa cân bằng. So với tầm giá thì rất đáng tiền.', NULL, 65, 0, 0, 0, 'visible', '2026-03-11 09:10:00', '2026-03-11 09:10:00'),
(15, 5, 3, 4, 'Không gian sang nhưng khẩu phần nhỏ', 'Mỗi món đều đẹp và kỹ thuật tốt, nhưng nếu ai ăn nhiều sẽ thấy hơi thiếu. Phù hợp đi trải nghiệm hơn là ăn no.', NULL, 66, 0, 0, 0, 'visible', '2026-03-11 10:00:00', '2026-03-11 10:00:00'),
(16, 6, 4, 5, 'Tiệm bánh xinh và consistent', 'Bánh hôm nào cũng ổn định, staff dễ thương, bao bì đẹp. Giá nhỉnh nhưng xứng đáng.', NULL, 64, 0, 0, 0, 'visible', '2026-03-11 11:00:00', '2026-03-11 11:00:00'),
(17, 7, 5, 4, 'Smoothie bowl tươi và vừa bụng', 'Món lên đẹp, topping phong phú. Hợp với người muốn bữa nhẹ mà vẫn đủ no.', NULL, 56, 0, 0, 0, 'visible', '2026-03-11 12:00:00', '2026-03-11 12:00:00'),
(18, 4, 6, 5, 'Làm việc rất cuốn', 'Wifi ổn, ổ cắm nhiều, cà phê pha chuẩn. Mình có thể ngồi gần nửa ngày mà không bí bách.', NULL, 71, 0, 0, 0, 'visible', '2026-03-11 13:00:00', '2026-03-11 13:00:00'),
(19, 2, 7, 3, 'Ổn nhưng chưa quá nổi bật', 'Quán sạch sẽ, topping vừa phải, nước lèo thanh. Cá nhân mình vẫn thích vị đậm hơn một chút.', NULL, 50, 0, 0, 0, 'visible', '2026-03-11 14:00:00', '2026-03-11 14:00:00'),
(20, 3, 8, 5, 'Dịch vụ rất chuyên nghiệp', 'Từ lúc đón khách tới lúc giới thiệu món đều rất mượt. Đây là nơi mình sẽ dẫn khách quan trọng đi ăn.', NULL, 72, 0, 0, 0, 'visible', '2026-03-11 15:00:00', '2026-03-11 15:00:00'),
(21, 4, 9, 4, 'Ổn cho buổi trà chiều', 'Không gian ấm, bánh dễ ăn, cappuccino vừa miệng. Điểm cộng là staff hỗ trợ nhiệt tình.', NULL, 57, 0, 0, 0, 'visible', '2026-03-11 16:00:00', '2026-03-11 16:00:00'),
(22, 6, 10, 5, 'Menu healthy đa dạng', 'Không chỉ salad mà còn có bánh ngọt healthy nên đi cùng bạn bè vẫn dễ chọn món.', NULL, 68, 0, 0, 0, 'visible', '2026-03-11 16:40:00', '2026-03-11 16:40:00'),
(23, 5, 11, 4, 'Hoàng hôn đẹp, hợp hẹn hò', 'Nên đặt bàn trước khi đi cuối tuần. Không gian dễ tạo cảm giác chill, hợp nói chuyện.', NULL, 58, 0, 0, 0, 'visible', '2026-03-11 17:10:00', '2026-03-11 17:10:00'),
(24, 3, 12, 5, 'Cơm tấm ngon và phục vụ nhanh', 'Rất phù hợp dân văn phòng bữa trưa. Sườn tẩm vừa, không bị quá ngọt.', NULL, 61, 0, 0, 0, 'visible', '2026-03-11 17:40:00', '2026-03-11 17:40:00');

INSERT INTO comments (id, review_id, user_id, content, created_at, updated_at) VALUES
(1, 1, 3, 'Mình cũng mê món avocado toast ở đây.', '2026-03-10 08:25:00', '2026-03-10 08:25:00'),
(2, 1, 6, 'Cho mình hỏi cuối tuần có đông quá không?', '2026-03-10 08:28:00', '2026-03-10 08:28:00'),
(3, 2, 2, 'Bánh mì ở đây mình thích phần pate nhất.', '2026-03-10 08:45:00', '2026-03-10 08:45:00'),
(4, 3, 4, 'Nghe hấp dẫn quá, chắc phải dành dịp đặc biệt ghé thử.', '2026-03-10 09:15:00', '2026-03-10 09:15:00'),
(5, 4, 2, 'Tart ở đây đúng là rất xinh và ngon.', '2026-03-10 09:50:00', '2026-03-10 09:50:00'),
(6, 5, 7, 'Mình thấy bowl salmon ở đây cũng khá ổn.', '2026-03-10 10:15:00', '2026-03-10 10:15:00'),
(7, 6, 4, 'Quán này mình cũng hay ngồi làm việc buổi sáng.', '2026-03-10 11:20:00', '2026-03-10 11:20:00'),
(8, 7, 3, 'Ăn đêm ổn thật, mình hay ghé sau 10h.', '2026-03-10 12:20:00', '2026-03-10 12:20:00'),
(9, 8, 2, 'Service ở đây đúng kiểu fine dining.', '2026-03-10 13:25:00', '2026-03-10 13:25:00'),
(10, 9, 4, 'Cookie ở đây ngon nhưng hết sớm.', '2026-03-10 14:30:00', '2026-03-10 14:30:00'),
(11, 10, 6, 'Raw cheesecake nghe mê quá.', '2026-03-10 15:20:00', '2026-03-10 15:20:00'),
(12, 11, 5, 'Rooftop này đi chiều tối là đẹp nhất.', '2026-03-10 16:20:00', '2026-03-10 16:20:00'),
(13, 12, 2, 'Cơm tấm này đúng là cứu đói trưa văn phòng.', '2026-03-10 16:45:00', '2026-03-10 16:45:00'),
(14, 13, 7, 'Chuẩn luôn, cuối tuần mình toàn phải đi sớm.', '2026-03-11 08:40:00', '2026-03-11 08:40:00'),
(15, 14, 3, 'Mình còn thích phần sốt bơ riêng của quán này.', '2026-03-11 09:22:00', '2026-03-11 09:22:00'),
(16, 15, 6, 'Đi experience thì rất hợp.', '2026-03-11 10:20:00', '2026-03-11 10:20:00'),
(17, 16, 4, 'Bao bì đem về cũng rất xinh luôn.', '2026-03-11 11:20:00', '2026-03-11 11:20:00'),
(18, 17, 2, 'Món này ăn trưa khá nhẹ nhàng.', '2026-03-11 12:15:00', '2026-03-11 12:15:00'),
(19, 18, 3, 'Mình thích khu ngồi gần cửa sổ của quán.', '2026-03-11 13:18:00', '2026-03-11 13:18:00'),
(20, 19, 7, 'Mình lại thích vị thanh kiểu này.', '2026-03-11 14:14:00', '2026-03-11 14:14:00'),
(21, 20, 5, 'Nghe review xong muốn đặt bàn ngay.', '2026-03-11 15:18:00', '2026-03-11 15:18:00'),
(22, 21, 6, 'Đi buổi sáng chắc sẽ vắng hơn chút.', '2026-03-11 16:10:00', '2026-03-11 16:10:00'),
(23, 22, 4, 'Quán healthy nhưng vẫn vui vì menu đa dạng.', '2026-03-11 16:55:00', '2026-03-11 16:55:00'),
(24, 24, 2, 'Cơm tấm này mình ăn 2 lần đều ổn định.', '2026-03-11 17:52:00', '2026-03-11 17:52:00');

INSERT INTO review_likes (user_id, review_id, created_at) VALUES
(3, 1, '2026-03-10 08:25:00'), (4, 1, '2026-03-10 08:26:00'), (5, 1, '2026-03-10 08:27:00'),
(2, 2, '2026-03-10 08:46:00'), (6, 2, '2026-03-10 08:47:00'),
(2, 3, '2026-03-10 09:16:00'), (3, 3, '2026-03-10 09:17:00'), (4, 3, '2026-03-10 09:18:00'), (6, 3, '2026-03-10 09:19:00'),
(2, 4, '2026-03-10 09:51:00'), (5, 4, '2026-03-10 09:52:00'),
(7, 5, '2026-03-10 10:16:00'), (2, 6, '2026-03-10 11:21:00'), (4, 6, '2026-03-10 11:22:00'), (7, 6, '2026-03-10 11:23:00'),
(3, 8, '2026-03-10 13:26:00'), (4, 8, '2026-03-10 13:27:00'), (6, 8, '2026-03-10 13:28:00'),
(2, 10, '2026-03-10 15:21:00'), (6, 10, '2026-03-10 15:22:00'),
(2, 12, '2026-03-10 16:46:00'), (3, 12, '2026-03-10 16:47:00'), (4, 12, '2026-03-10 16:48:00'),
(7, 14, '2026-03-11 09:23:00'), (2, 15, '2026-03-11 10:21:00'), (3, 18, '2026-03-11 13:19:00'),
(5, 20, '2026-03-11 15:19:00'), (2, 22, '2026-03-11 16:56:00'), (4, 24, '2026-03-11 17:53:00');

INSERT INTO review_reports (id, review_id, user_id, reason, created_at) VALUES
(1, 19, 4, 'Nội dung hơi thiếu chi tiết và có ngôn từ tiêu cực.', '2026-03-11 14:40:00'),
(2, 19, 6, 'Cần admin xem lại vì thông tin chưa đầy đủ.', '2026-03-11 14:45:00'),
(3, 7, 2, 'Mô tả chung chung, có thể cần kiểm duyệt thêm.', '2026-03-11 12:30:00'),
(4, 21, 3, 'Một vài nhận xét về phục vụ chưa thật rõ ràng.', '2026-03-11 16:30:00');

INSERT INTO place_bookmarks (user_id, place_id, created_at) VALUES
(2, 3, '2026-03-11 09:30:00'), (2, 8, '2026-03-11 09:31:00'), (2, 11, '2026-03-11 09:31:30'),
(3, 2, '2026-03-11 09:40:00'), (3, 7, '2026-03-11 09:41:00'), (3, 12, '2026-03-11 09:42:00'),
(4, 5, '2026-03-11 10:10:00'), (4, 10, '2026-03-11 10:11:00'),
(5, 3, '2026-03-11 10:20:00'), (5, 8, '2026-03-11 10:21:00'),
(6, 4, '2026-03-11 10:30:00'), (6, 9, '2026-03-11 10:31:00'),
(7, 1, '2026-03-11 10:40:00'), (7, 12, '2026-03-11 10:41:00');

INSERT INTO collections (id, user_id, name, description, privacy, created_at, updated_at) VALUES
(1, 2, 'Weekend Brunch Picks', 'Những quán brunch hợp cuối tuần và cà phê sáng.', 'public', '2026-03-10 18:00:00', '2026-03-10 18:00:00'),
(2, 3, 'Street Food Sài Gòn', 'Danh sách món ngon giá tốt, ăn nhanh nhưng chất lượng.', 'public', '2026-03-10 18:10:00', '2026-03-10 18:10:00'),
(3, 4, 'Healthy Day', 'Các quán healthy, vegan, smoothie bowl để ăn lành mạnh.', 'public', '2026-03-10 18:20:00', '2026-03-10 18:20:00'),
(4, 5, 'Date Night Fine Dining', 'Nhà hàng sang trọng cho dịp quan trọng.', 'private', '2026-03-10 18:30:00', '2026-03-10 18:30:00'),
(5, 6, 'Dessert Tour', 'Tiệm bánh và dessert bar đáng ghé cuối tuần.', 'public', '2026-03-10 18:40:00', '2026-03-10 18:40:00'),
(6, 7, 'Lunch Quick Save', 'Chỗ ăn trưa ngon, lên món nhanh.', 'private', '2026-03-10 18:50:00', '2026-03-10 18:50:00');

INSERT INTO collection_places (collection_id, place_id, created_at) VALUES
(1, 1, '2026-03-10 18:01:00'), (1, 6, '2026-03-10 18:01:30'), (1, 11, '2026-03-10 18:02:00'),
(2, 2, '2026-03-10 18:11:00'), (2, 7, '2026-03-10 18:11:30'), (2, 12, '2026-03-10 18:12:00'),
(3, 5, '2026-03-10 18:21:00'), (3, 10, '2026-03-10 18:21:30'),
(4, 3, '2026-03-10 18:31:00'), (4, 8, '2026-03-10 18:31:30'),
(5, 4, '2026-03-10 18:41:00'), (5, 9, '2026-03-10 18:41:30'),
(6, 2, '2026-03-10 18:51:00'), (6, 12, '2026-03-10 18:51:30');

INSERT INTO user_follows (follower_id, following_id, created_at) VALUES
(2, 3, '2026-03-10 19:00:00'), (2, 5, '2026-03-10 19:00:30'),
(3, 2, '2026-03-10 19:01:00'), (3, 7, '2026-03-10 19:01:30'),
(4, 2, '2026-03-10 19:02:00'), (4, 6, '2026-03-10 19:02:30'),
(5, 3, '2026-03-10 19:03:00'), (5, 6, '2026-03-10 19:03:30'),
(6, 2, '2026-03-10 19:04:00'), (7, 3, '2026-03-10 19:04:30'), (7, 5, '2026-03-10 19:05:00');

INSERT INTO notifications (id, user_id, actor_id, type, reference_id, message, is_read, created_at) VALUES
(1, 2, 3, 'comment_review', 1, 'đã bình luận review của bạn: "Brunch đẹp và đồ uống cân bằng"', 0, '2026-03-10 08:25:00'),
(2, 2, 6, 'comment_review', 1, 'đã bình luận review của bạn: "Brunch đẹp và đồ uống cân bằng"', 1, '2026-03-10 08:28:00'),
(3, 3, 2, 'comment_review', 2, 'đã bình luận review của bạn: "Bánh mì giòn nóng, nhân nhiều"', 0, '2026-03-10 08:45:00'),
(4, 5, 4, 'comment_review', 3, 'đã bình luận review của bạn: "Tasting menu đáng thử cho dịp đặc biệt"', 1, '2026-03-10 09:15:00'),
(5, 6, 2, 'comment_review', 4, 'đã bình luận review của bạn: "Quầy bánh nhỏ nhưng rất tinh tế"', 0, '2026-03-10 09:50:00'),
(6, 4, 7, 'comment_review', 5, 'đã bình luận review của bạn: "Healthy bowl đủ chất, sốt ngon"', 0, '2026-03-10 10:15:00'),
(7, 2, 3, 'like_review', 1, 'đã thích review của bạn về Morning Dew Cafe', 0, '2026-03-10 08:25:00'),
(8, 5, 2, 'like_review', 3, 'đã thích review của bạn về Cloud Nine Dining', 1, '2026-03-10 09:16:00'),
(9, 6, 4, 'like_review', 10, 'đã thích review của bạn về Raw Bliss Hub', 0, '2026-03-10 15:21:00'),
(10, 3, 2, 'follow_user', 2, 'đã bắt đầu theo dõi bạn', 0, '2026-03-10 19:00:00'),
(11, 5, 7, 'follow_user', 7, 'đã bắt đầu theo dõi bạn', 0, '2026-03-10 19:05:00'),
(12, 2, 1, 'report_status', 1, 'Admin đã tiếp nhận một báo cáo liên quan đến review của bạn.', 1, '2026-03-11 15:00:00');

UPDATE reviews r
LEFT JOIN (
    SELECT review_id, COUNT(*) AS total
    FROM review_likes
    GROUP BY review_id
) rl ON rl.review_id = r.id
LEFT JOIN (
    SELECT review_id, COUNT(*) AS total
    FROM review_reports
    GROUP BY review_id
) rr ON rr.review_id = r.id
SET r.helpful_count = COALESCE(rl.total, 0),
    r.report_count = COALESCE(rr.total, 0),
    r.rank_score = ROUND((r.rating * 1.5) + (COALESCE(rl.total, 0) * 1.2) + (r.verified_score * 0.8) - (COALESCE(rr.total, 0) * 1.5), 2);

UPDATE places p
LEFT JOIN (
    SELECT place_id, ROUND(AVG(rating), 2) AS avg_rating, COUNT(*) AS review_count
    FROM reviews
    WHERE status = 'visible'
    GROUP BY place_id
) x ON x.place_id = p.id
SET p.avg_rating = COALESCE(x.avg_rating, 0),
    p.review_count = COALESCE(x.review_count, 0);

ALTER TABLE users AUTO_INCREMENT = 8;
ALTER TABLE categories AUTO_INCREMENT = 6;
ALTER TABLE places AUTO_INCREMENT = 13;
ALTER TABLE reviews AUTO_INCREMENT = 25;
ALTER TABLE comments AUTO_INCREMENT = 25;
ALTER TABLE review_reports AUTO_INCREMENT = 5;
ALTER TABLE collections AUTO_INCREMENT = 7;
ALTER TABLE notifications AUTO_INCREMENT = 13;
