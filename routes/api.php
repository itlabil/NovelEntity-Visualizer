<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EntityController;
use Illuminate\Support\Facades\Route;

// Endpoint publik yang akan ditembak oleh Ekstensi Chrome
Route::get('/search-entity', [EntityController::class, 'search']);

// Route baru untuk kebutuhan Auto-Scanner Fase 2 (Opsi B)
Route::get('/novel-keywords', [EntityController::class, 'getAllAliases']);

// Rute Autentikasi JWT
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
});
