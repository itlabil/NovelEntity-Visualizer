<?php

use App\Http\Controllers\Api\EntityController;
use Illuminate\Support\Facades\Route;

// Endpoint publik yang akan ditembak oleh Ekstensi Chrome
Route::get('/search-entity', [EntityController::class, 'search']);

// Route baru untuk kebutuhan Auto-Scanner Fase 2 (Opsi B)
Route::get('/novel-keywords', [EntityController::class, 'getAllAliases']);
