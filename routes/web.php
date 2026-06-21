<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::prefix('forgot-password')->group(function () {
    Route::post('/send-code', [ForgotPasswordController::class, 'sendCode'])->name('password.send-code');
    Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify-code');
    Route::post('/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
});
