<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\RegencyApiConroller;
use App\Http\Controllers\Api\SchoolApiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/schools', [SchoolController::class, 'index'])
        ->name('schools');
});

Route::get('/visitor', function () {
    $key = 'visitor_' . now()->format('Y-m-d');
    cache()->increment($key);
    return cache()->get($key);
});


require __DIR__ . '/auth.php';
