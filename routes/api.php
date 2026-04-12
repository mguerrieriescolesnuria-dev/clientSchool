<?php

declare(strict_types=1);

use App\Http\Router;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;

// Teachers Routes
Router::get('api/teachers', [TeacherController::class, 'index']);
Router::get('api/teachers/{id}', [TeacherController::class, 'show']);
Router::post('api/teachers', [TeacherController::class, 'store']);
Router::put('api/teachers/{id}', [TeacherController::class, 'update']);
Router::delete('api/teachers/{id}', [TeacherController::class, 'destroy']);

// Students Routes
Router::get('api/students', [StudentController::class, 'index']);
Router::get('api/students/{id}', [StudentController::class, 'show']);
Router::post('api/students', [StudentController::class, 'store']);
Router::put('api/students/{id}', [StudentController::class, 'update']);
Router::delete('api/students/{id}', [StudentController::class, 'destroy']);

// Subjects Routes
Router::get('api/subjects', [SubjectController::class, 'index']);
Router::get('api/subjects/{id}', [SubjectController::class, 'show']);
Router::post('api/subjects', [SubjectController::class, 'store']);
Router::put('api/subjects/{id}', [SubjectController::class, 'update']);
Router::delete('api/subjects/{id}', [SubjectController::class, 'destroy']);

// Health check
Router::get('api/health', fn() => json_response(['status' => 'OK'], 200));
Router::get('/', fn() => json_response(['message' => 'clientSchool API v1.0'], 200));
