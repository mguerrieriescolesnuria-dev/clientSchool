<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

class Router
{
    private static array $routes = [];

    public static function get(string $path, callable|array $handler): void
    {
        self::$routes['GET'][$path] = $handler;
    }

    public static function post(string $path, callable|array $handler): void
    {
        self::$routes['POST'][$path] = $handler;
    }

    public static function put(string $path, callable|array $handler): void
    {
        self::$routes['PUT'][$path] = $handler;
    }

    public static function delete(string $path, callable|array $handler): void
    {
        self::$routes['DELETE'][$path] = $handler;
    }

    public static function route(string $method, string $uri): array|null
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $uri = ltrim($uri, '/');

        if (empty($uri)) {
            $uri = '/';
        }

        // Check exact match
        if (isset(self::$routes[$method][$uri])) {
            return [
                'handler' => self::$routes[$method][$uri],
                'params' => []
            ];
        }

        // Check pattern match (e.g., /api/teachers/{id})
        foreach (self::$routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                // Extract parameter names
                preg_match_all('/\{([^}]+)\}/', $route, $paramNames);
                $params = [];
                foreach ($paramNames[1] as $index => $name) {
                    $params[$name] = $matches[$index] ?? null;
                }

                return [
                    'handler' => $handler,
                    'params' => $params
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

        ['handler' => $handler, 'params' => $params] = $route;

        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method(...array_values($params));
        } elseif (is_callable($handler)) {
            $handler(...array_values($params));
        }
    }
}
