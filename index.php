<?php

declare(strict_types=1);

/**
 * Front Controller - REST API Entry Point
 * 
 * Initializes the REST API with Router and dispatches requests
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/helpers.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists(__DIR__ . '/.env')) {
    $dotenv->load();
}

// Set headers for CORS and API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

use App\Infrastructure\Web\Router;
use App\Infrastructure\Web\TeacherController;
use App\Infrastructure\Web\StudentController;
use App\Infrastructure\Web\SubjectController;

// Register routes
Router::get('api/teachers', [TeacherController::class, 'index']);
Router::get('api/teachers/{id}', [TeacherController::class, 'show']);
Router::post('api/teachers', [TeacherController::class, 'store']);
Router::put('api/teachers/{id}', [TeacherController::class, 'update']);
Router::delete('api/teachers/{id}', [TeacherController::class, 'destroy']);

Router::get('api/students', [StudentController::class, 'index']);
Router::get('api/students/{id}', [StudentController::class, 'show']);
Router::post('api/students', [StudentController::class, 'store']);
Router::put('api/students/{id}', [StudentController::class, 'update']);
Router::delete('api/students/{id}', [StudentController::class, 'destroy']);

Router::get('api/subjects', [SubjectController::class, 'index']);
Router::get('api/subjects/{id}', [SubjectController::class, 'show']);
Router::post('api/subjects', [SubjectController::class, 'store']);
Router::put('api/subjects/{id}', [SubjectController::class, 'update']);
Router::delete('api/subjects/{id}', [SubjectController::class, 'destroy']);

Router::get('api/health', fn() => json_response(['status' => 'OK'], 200));
Router::get('/', fn() => json_response(['message' => 'clientSchool API v1.0'], 200));

// Dispatch the request
try {
    Router::dispatch();
} catch (\Exception $e) {
    error_response($e->getMessage(), 500);
}
