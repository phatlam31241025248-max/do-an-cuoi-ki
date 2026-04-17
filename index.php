<?php

require_once __DIR__ . '/bootstrap.php';

$router = new Core\Router();
require_once __DIR__ . '/routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

if ($scriptDir && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir)) ?: '/';
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);