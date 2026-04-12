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
        header('Content-Type: application/json');
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