<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

// =======================
// AUTH ROUTES
// =======================

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'loginPost'])->name('api.login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('api.logout');
});


Route::middleware('auth:sanctum')->group(function () {
    
// =======================
// ROLE ROUTES
// =======================
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.roles.index');
        Route::post('/', [RoleController::class, 'store'])->name('api.roles.store');
        Route::get('/permissions', [RoleController::class, 'permissions'])->name('api.roles.permissions');
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.roles.show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('api.roles.update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.roles.destroy');
    });

// =======================
// USER ROUTES
// =======================
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
        Route::post('/', [UserController::class, 'store'])->name('api.users.store');
        Route::get('/roles', [UserController::class, 'roles'])->name('api.users.roles');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');
        Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');
    });
});

