<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

class Router
{
    private static array $routes = [];

    public static function get(string $path, callable|array $handler, array $middleware = []): void
    {
        self::register('GET', $path, $handler, $middleware);
    }

    public static function post(string $path, callable|array $handler, array $middleware = []): void
    {
        self::register('POST', $path, $handler, $middleware);
    }

    public static function put(string $path, callable|array $handler, array $middleware = []): void
    {
        self::register('PUT', $path, $handler, $middleware);
    }

    public static function delete(string $path, callable|array $handler, array $middleware = []): void
    {
        self::register('DELETE', $path, $handler, $middleware);
    }

    private static function register(string $method, string $path, callable|array $handler, array $middleware): void
    {
        self::$routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public static function route(string $method, string $uri): array|null
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $uri = ltrim($uri, '/');

        if (empty($uri)) {
            $uri = '/';
        }

        if (isset(self::$routes[$method][$uri])) {
            return [
                'handler' => self::$routes[$method][$uri]['handler'],
                'params' => [],
                'middleware' => self::$routes[$method][$uri]['middleware'],
            ];
        }

        foreach (self::$routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                
                preg_match_all('/\{([^}]+)\}/', $route, $paramNames);
                $params = [];
                foreach ($paramNames[1] as $index => $name) {
                    $params[$name] = $matches[$index] ?? null;
                }

                return [
                    'handler' => $handler['handler'],
                    'params' => $params,
                    'middleware' => $handler['middleware'],
                ];
            }
        }

        return null;
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        $route = self::route($method, $uri);

        if ($route === null) {
            error_response('Route not found', 404);
        }

        ['handler' => $handler, 'params' => $params, 'middleware' => $middleware] = $route;

        foreach ($middleware as $middlewareClass) {
            $instance = new $middlewareClass();
            $instance->handle();
        }

        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method(...array_values($params));
        } elseif (is_callable($handler)) {
            $handler(...array_values($params));
        }
    }
}
