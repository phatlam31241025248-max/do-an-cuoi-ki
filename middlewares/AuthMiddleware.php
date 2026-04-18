<?php

namespace Middlewares;

use Helpers\Auth;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            header('Location: ' . url('login'));
            exit;
        }
    }
}
