<?php

namespace Helpers;

class Csrf
{
    public static function token(): string
    {
        if (!Session::get('_csrf_token')) {
            Session::put('_csrf_token', bin2hex(random_bytes(32)));
        }

        return Session::get('_csrf_token');
    }

    public static function verify(?string $token): bool
    {
        return hash_equals((string) Session::get('_csrf_token'), (string) $token);
    }
}
