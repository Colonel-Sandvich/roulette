<?php

declare(strict_types=1);

use App\Http\Controllers\BetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouletteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('profile')
    ->name('profile.')
    ->middleware(['auth'])
    ->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });
    });

Route::prefix('roulette')
    ->name('roulette.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', [RouletteController::class, 'index'])->name('index');
    });

Route::prefix('bet')
    ->name('bet.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::post('/', [BetController::class, 'store'])->name('store');
    });

require __DIR__ . '/auth.php';
