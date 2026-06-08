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

    // route resource untuk novels
    Route::post("/novels/bulk-delete", [Account\NovelController::class, 'bulkDelete'])->name("account.novels.bulkDelete");
    Route::resource('/novels', Account\NovelController::class, ['as' => 'account']);

    // route resource untuk entities
    Route::get('/novels/{novel}/entities/create', [Account\EntityController::class, 'create'])->name('account.entities.create');
    Route::post('/novels/{novel}/entities', [Account\EntityController::class, 'store'])->name('account.entities.store');

    Route::get('/entities/{entity}/edit', [Account\EntityController::class, 'edit'])->name('account.entities.edit');
    Route::put('/entities/{entity}', [Account\EntityController::class, 'update'])->name('account.entities.update');

    Route::post("/entities/bulk-delete", [Account\EntityController::class, 'bulkDelete'])->name("account.entities.bulkDelete");
    Route::resource('/entities', Account\EntityController::class, ['except' => ['create','store','edit','update'], 'as' => 'account']);

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
