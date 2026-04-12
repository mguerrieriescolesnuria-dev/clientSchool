<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/helpers.php';

// Load environment variables
$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv->load();
}

// Set headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load routes
require_once __DIR__ . '/../routes/api.php';

// Dispatch the request
use App\Http\Router;

try {
    Router::dispatch();
} catch (\Exception $e) {
    error_response($e->getMessage(), 500);
}
