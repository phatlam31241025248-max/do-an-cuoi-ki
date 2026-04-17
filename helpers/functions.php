<?php

use Helpers\Auth;
use Helpers\Csrf;
use Helpers\Flash;

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $configs = [];

        [$file, $item] = array_pad(explode('.', $key, 2), 2, null);

        if (!isset($configs[$file])) {
            $path = __DIR__ . '/../config/' . $file . '.php';
            $configs[$file] = file_exists($path) ? require $path : [];
        }

        return $item ? ($configs[$file][$item] ?? $default) : ($configs[$file] ?? $default);
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $base = dirname(__DIR__);
        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return base_path(ltrim($path, '/'));
    }
}


if (!function_exists('is_absolute_url')) {
    function is_absolute_url(string $path): bool
    {
        return (bool) preg_match('#^(?:https?:)?//#i', $path)
            || str_starts_with($path, 'data:')
            || str_starts_with($path, 'mailto:')
            || str_starts_with($path, 'tel:');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        if ($path === '' || is_absolute_url($path)) {
            return $path;
        }

        return rtrim(config('app.base_url'), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        if ($path === '' || $path === '/') {
            return rtrim(config('app.base_url'), '/');
        }

        if (is_absolute_url($path)) {
            return $path;
        }

        return rtrim(config('app.base_url'), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect_url')) {
    function redirect_url(string $path = ''): never
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array
    {
        return Auth::user();
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return Auth::check();
    }
}

if (!function_exists('has_role')) {
    function has_role(string $roleName): bool
    {
        return Auth::hasRole($roleName);
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('set_old')) {
    function set_old(array $data): void
    {
        $_SESSION['_old'] = $data;
    }
}

if (!function_exists('clear_old')) {
    function clear_old(): void
    {
        unset($_SESSION['_old']);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): mixed
    {
        if ($message !== null) {
            Flash::set($key, $message);
            return null;
        }

        return Flash::get($key);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(Csrf::token()) . '">';
    }
}

if (!function_exists('format_price_range')) {
    function format_price_range(?string $value): string
    {
        return $value ?: 'Đang cập nhật';
    }
}

if (!function_exists('format_time_ago')) {
    function format_time_ago(?string $date): string
    {
        if (!$date) {
            return '';
        }

        $timestamp = strtotime($date);
        $diff = time() - $timestamp;

        if ($diff < 60) return 'Vừa xong';
        if ($diff < 3600) return floor($diff / 60) . ' phút trước';
        if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
        if ($diff < 604800) return floor($diff / 86400) . ' ngày trước';
        return date('d/m/Y H:i', $timestamp);
    }
}

if (!function_exists('render_stars')) {
    function render_stars(float $rating): string
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $html .= '<i class="bi bi-star' . ($i <= round($rating) ? '-fill text-warning' : ' text-secondary-subtle') . '"></i>';
        }

        return $html;
    }
}

if (!function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        $map = [
            'visible' => 'success',
            'hidden' => 'secondary',
            'active' => 'success',
            'banned' => 'danger',
            'public' => 'primary',
            'private' => 'dark',
        ];

        $class = $map[$status] ?? 'secondary';

        return '<span class="badge text-bg-' . $class . '">' . e(ucfirst($status)) . '</span>';
    }
}
