<?php

namespace Services;

use Helpers\Auth;
use Helpers\Validator;
use Models\UserModel;
use Models\RoleModel;
use Models\UserRoleModel;

class AuthService
{
    private UserModel $users;
    private RoleModel $roles;
    private UserRoleModel $userRoles;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->roles = new RoleModel();
        $this->userRoles = new UserRoleModel();
    }

    public function register(array $data): array
    {
        $errors = Validator::validate($data, [
            'full_name' => ['required', 'min:3', 'max:120'],
            'username' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (($data['password'] ?? '') !== ($data['password_confirmation'] ?? '')) {
            $errors['password_confirmation'][] = 'Xác nhận mật khẩu không khớp.';
        }

        if ($this->users->findByEmailOrUsername($data['email'] ?? '')) {
            $errors['email'][] = 'Email đã tồn tại.';
        }

        if ($this->users->findByUsername($data['username'] ?? '')) {
            $errors['username'][] = 'Username đã tồn tại.';
        }

        if ($errors) {
            return ['success' => False, 'errors' => $errors];
        }

        $userId = $this->users->create([
            'full_name' => trim($data['full_name']),
            'username' => trim($data['username']),
            'email' => trim($data['email']),
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'avatar' => config('app.default_avatar'),
            'bio' => 'Food lover mới gia nhập FoodSpace ✨',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $role = $this->roles->findByName('user');
        if ($role) {
            $this->userRoles->assignRole($userId, (int) $role['id']);
        }

        $user = $this->users->find($userId);
        $user['roles'] = ['user'];
        Auth::login($user);

        return ['success' => True, 'user' => $user];
    }

    public function login(array $data): array
    {
        $errors = Validator::validate($data, [
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if ($errors) {
            return ['success' => False, 'errors' => $errors];
        }

        $user = $this->users->findByEmailOrUsername(trim($data['login']));
        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            return ['success' => False, 'errors' => ['login' => ['Thông tin đăng nhập không chính xác.']]];
        }

        if (($user['status'] ?? 'active') !== 'active') {
            return ['success' => False, 'errors' => ['login' => ['Tài khoản đã bị khóa.']]];
        }

        $user['roles'] = $this->userRoles->getRoleNamesForUser((int) $user['id']);
        Auth::login($user);

        return ['success' => True, 'user' => $user];
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
