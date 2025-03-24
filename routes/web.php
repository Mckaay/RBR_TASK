<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function (): void {
    Route::get('/login', fn () => view('login'));
});

require __DIR__ . '/auth.php';
