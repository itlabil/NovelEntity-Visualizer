<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EntityController;

Route::get('/novel-keywords', [EntityController::class, 'getAllAliases']);
