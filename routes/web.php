<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;


Route::get('user-register', [UserController::class, 'RegistrationForm'])->name('user.register');
Route::post('register', [UserController::class, 'userRegister'])->name('register');
Route::post('verify-otp', [UserController::class, 'VerifyOTP'])->name('verify-otp');

// Login routes with middleware to prevent logged-in users from accessing the login page
Route::middleware('guest')->group(function () {
    Route::get('login', [UserController::class, 'LoginPage'])->name('login');
    Route::post('user-login', [UserController::class, 'Login'])->name('user.login');
});

// Home route with middleware to ensure only authenticated users can access it
Route::middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('checkout/{product}', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');


    Route::get('admin-index', [AdminController::class, 'index'])->name('admin-index');
});



// Paypal gateway
Route::post('/create-payment', [PayPalController::class, 'createPayment'])->name('create.payment');
Route::get('/execute-payment', [PayPalController::class, 'executePayment'])->name('paypal.success');
Route::get('/cancel-payment', [PayPalController::class, 'cancelPayment'])->name('paypal.cancel');
