<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskShareController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function (): void {
    Route::middleware(['guest'])->group(function (): void {
        Route::get('/login', fn() => view('auth.login'))->name('login');
        Route::get('/register', fn() => view('auth.register'))->name('register');
    });
});

Route::middleware(['web', 'auth'])->group(function (): void {
    // Task routes
    Route::prefix('task')->name('task.')->group(function (): void {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/update/{task}', [TaskController::class, 'edit'])->name('edit');
        Route::patch('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('delete');

        // Task sharing routes
        Route::get('/share/{token}', [TaskShareController::class, 'show'])->name('share');
        Route::post('/share/{task}', [TaskShareController::class, 'store'])->name('share.create');
    });

    Route::get('/', fn() => redirect()->route('task.index'));
});

require __DIR__ . '/auth.php';
