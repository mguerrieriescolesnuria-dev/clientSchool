<?php

declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('response')) {
    function response(array $data = [], int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('json_response')) {
    function json_response(array $data = [], int $status = 200, string $message = ''): void {
        $response = [
            'status' => $status,
            'data' => $data,
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        response($response, $status);
    }
}

if (!function_exists('error_response')) {
    function error_response(string $message = 'Error', int $status = 500, array $errors = []): void {
        $response = [
            'status' => $status,
            'message' => $message,
        ];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        response($response, $status);
    }
}

if (!function_exists('uuid')) {
    function uuid(): string {
        return str_replace('{{PLACEHOLDER}}', uniqid(mt_rand(), true), '{{PLACEHOLDER}}');
    }
}

if (!function_exists('dd')) {
    function dd(){
        foreach(func_get_args() as $arg){
            echo "<pre>";
            var_dump($arg);
            echo "</pre>";
        }
        die();
    }
}

if (!function_exists('redirect_response')) {
    function redirect_response(string $url): void
    {
        header('Location: ' . $url, true, 302);
        exit;
    }
}

if (!function_exists('html_response')) {
    function html_response(string $html, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!DOCTYPE html><html lang="ca"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>OAuth clientSchool</title></head><body style="font-family:Arial,sans-serif;max-width:760px;margin:40px auto;padding:0 16px;">' . $html . '</body></html>';
        exit;
    }
}

if (!function_exists('base64_url_encode')) {
    function base64_url_encode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}

if (!function_exists('base64_url_decode')) {
    function base64_url_decode(string $value): string
    {
        $padding = strlen($value) % 4;

        if ($padding > 0) {
            $value .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($value, '-_', '+/')) ?: '';
    }
}

if (!function_exists('authorization_header')) {
    function authorization_header(): ?string
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && is_string($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $name => $value) {
                if (strtolower($name) === 'authorization' && is_string($value)) {
                    return $value;
                }
            }
        }

        return null;
    }
}

if (!function_exists('current_auth_claims')) {
    function current_auth_claims(): ?array
    {
        if (!isset($_SERVER['AUTH_USER']) || !is_string($_SERVER['AUTH_USER'])) {
            return null;
        }

        $claims = json_decode($_SERVER['AUTH_USER'], true);

        return is_array($claims) ? $claims : null;
    }
}
