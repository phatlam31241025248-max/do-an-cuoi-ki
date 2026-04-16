<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, array $action, array $middlewares = []): void
    {
        $this->add('POST', $uri, $action, $middlewares);
    }

    private function add(string $method, string $uri, array $action, array $middlewares = []): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $uri);
        $pattern = '#^' . rtrim($pattern, '/') . '/?$#';

        $this->routes[] = compact('method', 'uri', 'action', 'middlewares', 'pattern');
    }

    public function dispatch(string $method, string $uri): void
    {
        $normalizedUri = '/' . trim($uri, '/');
        if ($normalizedUri === '//') {
            $normalizedUri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $normalizedUri, $matches)) {
                foreach ($route['middlewares'] as $middleware) {
                    $this->runMiddleware($middleware);
                }

                [$controllerClass, $methodName] = $route['action'];
                $controller = new $controllerClass();
                $params = array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                call_user_func_array([$controller, $methodName], array_values($params));
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function runMiddleware(string $middleware): void
    {
        if (str_contains($middleware, ':')) {
            [$class, $argument] = explode(':', $middleware, 2);
            (new $class())->handle($argument);
            return;
        }

        (new $middleware())->handle();
    }
}
