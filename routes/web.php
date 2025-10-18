<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminCarsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/admin/cars', [AdminCarsController::class, 'index'])->name('admin.cars');
    });

    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/payment/{reservation}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{reservation}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/status/{status}', [PaymentController::class, 'status'])->name('payment.status');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
});
