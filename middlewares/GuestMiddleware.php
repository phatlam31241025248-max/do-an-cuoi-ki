<?php

namespace Middlewares;

use Helpers\Auth;

class GuestMiddleware
{
    public function handle(): void
    {
        if (Auth::check()) {
            header('Location: ' . url(''));
            exit;
        }
    }
}
