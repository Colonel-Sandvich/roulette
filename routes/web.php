<?php

declare(strict_types=1);

use App\Http\Controllers\BetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouletteController;
use App\Http\Controllers\WalletController;
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
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

Route::prefix('roulette')
    ->name('roulette.')
    ->middleware(['auth', 'verified'])
    ->controller(RouletteController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/stream', 'stream')->name('stream');
        Route::get('/test-stream', 'testStream')->name('test-stream');
    });

Route::prefix('bet')
    ->name('bet.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::post('/', [BetController::class, 'store'])->name('store');
    });

Route::prefix('wallet')
    ->name('wallet.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::post('/', [WalletController::class, 'update'])->name(
            'update_balance',
        );
    });

require __DIR__ . '/auth.php';
