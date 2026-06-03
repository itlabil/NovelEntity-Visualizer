<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/**
 * home
 */
Route::get('/', function () {
    return view('auth.login');
});

//prefix route for account
Route::prefix('account')->middleware(['auth'])->group(function () {

    //dashboard
    Route::get('/dashboard', [Account\DashboardController::class, 'index'])->name('account.dashboard.index');

    // route resource untuk permissions
    Route::post("/permissions/bulk-delete", [Account\PermissionController::class, 'bulkDelete'])->name("account.permissions.bulkDelete");
    Route::resource('/permissions', Account\PermissionController::class, ['as' => 'account']);

    // route resource untuk roles
    Route::post("/roles/bulk-delete", [Account\RoleController::class, 'bulkDelete'])->name("account.roles.bulkDelete");
    Route::resource('/roles', Account\RoleController::class, ['as' => 'account']);

    // route resource untuk users
    Route::post("/users/bulk-delete", [Account\UserController::class, 'bulkDelete'])->name("account.users.bulkDelete");
    Route::resource('/users', Account\UserController::class, ['as' => 'account']);

});
