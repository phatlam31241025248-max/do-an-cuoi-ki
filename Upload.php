<?php

namespace Helpers;

class Upload
{
    public static function storeImage(?array $file, string $folder = 'reviews'): ?string
    {
        if (!$file || !isset($file['error'])) {
            return null;
        }

        if ((int) $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Tải ảnh lên thất bại. Vui lòng thử lại.');
        }

        $maxSize = (int) config('app.max_upload_size', 5 * 1024 * 1024);
        if ((int) ($file['size'] ?? 0) > $maxSize) {
            throw new \RuntimeException('Ảnh vượt quá dung lượng cho phép (tối đa 5MB).');
        }

        $tmpPath = $file['tmp_name'] ?? '';
        if (!$tmpPath || !is_uploaded_file($tmpPath)) {
            throw new \RuntimeException('Tệp tải lên không hợp lệ.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $finfo->file($tmpPath);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (!isset($allowed[$mime])) {
            throw new \RuntimeException('Chỉ hỗ trợ ảnh JPG, PNG, WEBP hoặc GIF.');
        }

        $relativeDir = 'uploads/' . trim($folder, '/');
        $targetDir = public_path($relativeDir);

        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
            throw new \RuntimeException('Không thể tạo thư mục lưu ảnh.');
        }

        $filename = date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
        $destination = rtrim($targetDir, '/\\') . '/' . $filename;

        if (!move_uploaded_file($tmpPath, $destination)) {
            throw new \RuntimeException('Không thể lưu ảnh lên máy chủ.');
        }

        // Luôn trả về path public có dấu / ở đầu
        return '/' . $relativeDir . '/' . $filename;
    }

    public static function delete(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        // Chuẩn hóa để delete được cả khi DB có hoặc không có dấu / đầu
        $normalizedPath = ltrim($relativePath, '/');
        $fullPath = public_path($normalizedPath);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}