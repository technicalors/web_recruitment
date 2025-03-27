<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController,
    DashboardController,
    UserController,
    JobController,
    NewsController,
    ConfigController,
    JobCategoryController,
    EmployerController,
};
use App\Http\Middleware\AdminAccess;
use App\Http\Middleware\RedirectIfAdmin;

Route::middleware(RedirectIfAdmin::class)->get('/admin', function () {
    return null;
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Cần đăng nhập admin
    Route::middleware([AdminAccess::class])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('jobs', JobController::class);
        Route::resource('job-categories', JobCategoryController::class);
        Route::resource('employers', EmployerController::class);
        Route::resource('news', NewsController::class);
        Route::resource('configs', ConfigController::class);
    });
});
