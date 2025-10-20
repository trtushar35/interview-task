<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RoleController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//for role management
Route::resource('role', RoleController::class);