<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminCarsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('welcome');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/payment/status', [PaymentController::class, 'webhook'])->name('payment.webhook');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/cars', [AdminCarsController::class, 'index'])->name('admin.cars');
    });

    // User / Reservation Routes
    Route::prefix('reservations')->group(function () {
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    });

    // Payment Routes
    Route::prefix('payment')->group(function () {
        Route::get('/{reservation}', [PaymentController::class, 'show'])->name('payment.show');
        Route::post('/{reservation}', [PaymentController::class, 'pay'])->name('payment.pay');
        Route::get('/status/{status}', [PaymentController::class, 'status'])->name('payment.status');
    });

    // User Routes
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
});
