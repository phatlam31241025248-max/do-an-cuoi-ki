<?php

namespace Middlewares;

use Helpers\Auth;

class RoleMiddleware
{
    public function handle(string $role): void
    {
        if (!Auth::check() || !Auth::hasRole($role)) {
            http_response_code(403);
            die('403 Forbidden');
        }
    }
}
