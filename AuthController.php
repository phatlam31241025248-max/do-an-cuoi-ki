<?php

use Core\BaseController;
use Helpers\Csrf;
use Services\AuthService;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Đăng nhập']);
    }

    public function login(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('login');
        }

        $result = $this->authService->login($this->request->all());
        if (!$result['success']) {
            set_old($this->request->all());
            flash('error', implode(' ', array_merge(...array_values($result['errors']))));
            $this->response->redirect('login');
        }

        clear_old();
        flash('success', 'Đăng nhập thành công!');
        $this->response->redirect('');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Đăng ký']);
    }

    public function register(): void
    {
        if (!Csrf::verify($this->request->input('_token'))) {
            flash('error', 'CSRF token không hợp lệ.');
            $this->response->redirect('register');
        }

        $result = $this->authService->register($this->request->all());
        if (!$result['success']) {
            set_old($this->request->all());
            flash('error', implode(' ', array_merge(...array_values($result['errors']))));
            $this->response->redirect('register');
        }

        clear_old();
        flash('success', 'Tạo tài khoản thành công!');
        $this->response->redirect('');
    }

    public function logout(): void
    {
        $this->authService->logout();
        flash('success', 'Bạn đã đăng xuất.');
        $this->response->redirect('');
    }
}
