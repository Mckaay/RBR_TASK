<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => json_encode(PHP_VERSION));

require __DIR__ . '/auth.php';
