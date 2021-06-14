<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Admins\RoleController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\Admins\PermissionController;
use App\Http\Controllers\Admins\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', 'verified', 'role: super-admin|admin|moderator|developer'])->group(function() {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('admins', AdminController::class)->parameters(['admins' => 'user'])->only(['index', 'update']);
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('permissions', PermissionController::class)->except(['create', 'show', 'edit']);
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);
});
