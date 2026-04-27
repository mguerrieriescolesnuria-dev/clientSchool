<?php

use App\Http\Controllers\ApiProxyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'landing'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::middleware('auth')->group(function (): void {
    Route::get('/app', [DashboardController::class, 'index'])->name('app.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('api')->group(function (): void {
        Route::get('/session/user', [DashboardController::class, 'user'])->name('api.session.user');
        Route::get('/resources/{resource}', [ApiProxyController::class, 'index'])->name('api.resources.index');
        Route::get('/resources/{resource}/{id}', [ApiProxyController::class, 'show'])->name('api.resources.show');
        Route::post('/resources/{resource}', [ApiProxyController::class, 'store'])->name('api.resources.store');
        Route::put('/resources/{resource}/{id}', [ApiProxyController::class, 'update'])->name('api.resources.update');
        Route::delete('/resources/{resource}/{id}', [ApiProxyController::class, 'destroy'])->name('api.resources.destroy');
    });
});
