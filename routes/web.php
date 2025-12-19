<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolApiController;
use App\Http\Controllers\Api\RegencyApiConroller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/schools-api', [SchoolApiController::class, 'index'])
        ->name('admin.schools.api');
    Route::get('/regency-api', [RegencyApiConroller::class, 'index'])
        ->name('admin.regency.api');
});

require __DIR__ . '/auth.php';
