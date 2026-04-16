<?php

require_once __DIR__ . '/helpers/functions.php';

date_default_timezone_set(config('app.timezone'));

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    $file = __DIR__ . '/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
        return;
    }

    $folders = ['controllers', 'models', 'services', 'core', 'helpers', 'middlewares'];
    foreach ($folders as $folder) {
        $file = __DIR__ . '/' . $folder . '/' . basename($classPath) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

\Helpers\Session::start();
\Helpers\Csrf::token();
