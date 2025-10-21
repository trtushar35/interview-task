<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//for role management
Route::resource('role', RoleController::class);

//for user management
Route::resource('user', UserController::class);