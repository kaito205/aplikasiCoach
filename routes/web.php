<?php

use App\Http\Controllers\DailyLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrototypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/prototypes/create', [PrototypeController::class, 'create'])
        ->name('prototypes.create');

    Route::post('/prototypes', [PrototypeController::class, 'store'])
        ->name('prototypes.store');

    Route::post('/daily-logs', [DailyLogController::class, 'store'])
        ->name('daily-logs.store');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
require __DIR__ . '/auth.php';
