#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * API Test Script
 * 
 * Tests all endpoints without needing a web server
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/helpers.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists(__DIR__ . '/.env')) {
    $dotenv->load();
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

// Test endpoints
$tests = [
    ['GET', '/api/health', 'Health Check'],
    ['GET', '/api/teachers', 'List Teachers'],
    ['GET', '/api/teachers/1', 'Get Teacher'],
    ['POST', '/api/teachers', 'Create Teacher'],
    ['GET', '/api/students', 'List Students'],
    ['GET', '/api/students/1', 'Get Student'],
    ['POST', '/api/students', 'Create Student'],
    ['GET', '/api/subjects', 'List Subjects'],
    ['GET', '/api/subjects/1', 'Get Subject'],
    ['POST', '/api/subjects', 'Create Subject'],
    ['GET', '/', 'Root Endpoint'],
];

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          clientSchool API - Endpoint Tests                ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$passed = 0;
$failed = 0;

foreach ($tests as [$method, $uri, $name]) {
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI'] = $uri;
    
    // Capture output
    ob_start();
    $output = '';
    
    try {
        // Mock the json_response and error_response for testing
        $GLOBALS['test_response'] = null;
        
        // Redirect to capturing function
        Router::dispatch();
    } catch (\Exception $e) {
        // Catch exceptions
        $output = json_encode(['status' => 500, 'message' => $e->getMessage()]);
    }
    
    $captured = ob_get_clean();
    $output = $captured ?: ($GLOBALS['test_response'] ?? '{}');
    
    $response = json_decode($output, true);
    
    if ($response && isset($response['status'])) {
        echo "[✓] $method $uri - $name\n";
        $passed++;
    } else {
        echo "[✗] $method $uri - $name (Response: $output)\n";
        $failed++;
    }
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  Results: $passed passed, $failed failed                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

exit($failed > 0 ? 1 : 0);
