<?php

use App\Http\Controllers\Api\SchoolApiController;
use App\Http\Controllers\Api\VillageApiController;
use App\Http\Controllers\Api\ZonasiApiController;
use App\Http\Controllers\Api\DistrictApiController;
use App\Http\Controllers\Api\RegionApiController;

Route::prefix('v1')->group(function () {
    Route::get('/schools', [SchoolApiController::class, 'index']);
    Route::get('/schools/{id}', [SchoolApiController::class, 'show']);
    Route::post('/schools', [SchoolApiController::class, 'store']);
    Route::put('/schools/{id}', [SchoolApiController::class, 'update']);
    Route::delete('/schools/{id}', [SchoolApiController::class, 'destroy']);

    // Route::get('/districts/{regencyCode}', [DistrictApiController::class, 'index']);
    // Route::get('/villages', [VillageApiController::class, 'index']);
    Route::post('/zonasi/check', [ZonasiApiController::class, 'check']);

    Route::get('/districts', [RegionApiController::class, 'districts']);
    Route::get('/villages', [RegionApiController::class, 'villages']);
});
