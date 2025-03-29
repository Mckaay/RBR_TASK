<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function (): void {
    Route::middleware(['guest'])->group(function (): void {
        Route::get('/login', fn() => view('auth.login'))->name('login');
        Route::get('/register', fn() => view('auth.register'))->name('register');
    });
});

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::get('/', [TaskController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
