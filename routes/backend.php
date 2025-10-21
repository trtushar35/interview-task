<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;



Route::get('/login', [LoginController::class, 'loginPage'])->name('login.page');
Route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');

Route::middleware(['admin'])->group(function () {

    //for dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role management routes
    Route::resource('role', RoleController::class);

    // User management routes
    Route::resource('user', UserController::class);

    // Logout route
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
