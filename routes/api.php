<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EntityController;
use App\Models\Novel;

Route::get('/novel-keywords', [EntityController::class, 'getAllAliases']);
Route::get('/novels-list', function() {
    // Tarik hanya novel yang statusnya telah divalidasi 'approved' oleh admin sekolah!
    $novels = Novel::where('status', 'approved')
        ->with('user')
        ->get()
        ->map(function($novel) {
            return [
                'title'  => $novel->title,
                'slug'   => $novel->slug,
                'type'   => strtoupper($novel->type),
            ];
        });

    return response()->json([
        'status' => 'success',
        'data'   => $novels
    ]);
});