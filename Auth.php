<?php

namespace Helpers;

use Models\UserModel;
use Models\UserRoleModel;

class Auth
{
    public static function user(): ?array
    {
        $user = Session::get('auth_user');

        if ($user) {
            return $user;
        }

        $userId = Session::get('auth_user_id');
        if (!$userId) {
            return null;
        }

        $userModel = new UserModel();
        $user = $userModel->find((int) $userId);
        if (!$user) {
            return null;
        }

        $roleModel = new UserRoleModel();
        $user['roles'] = $roleModel->getRoleNamesForUser((int) $userId);
        Session::put('auth_user', $user);

        return $user;
    }

    public static function login(array $user): void
    {
        Session::put('auth_user_id', $user['id']);
        Session::put('auth_user', $user);
    }

    public static function logout(): void
    {
        Session::forget('auth_user_id');
        Session::forget('auth_user');
    }

    public static function check(): bool
    {
        return (bool) self::user();
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function hasRole(string $roleName): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        return in_array($roleName, $user['roles'] ?? [], true);
    }

    public static function refresh(): void
    {
        Session::forget('auth_user');
        self::user();
    }
}
