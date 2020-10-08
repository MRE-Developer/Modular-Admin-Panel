<?php

use Illuminate\Support\Facades\Route;
use TwoFactorAuth\Http\Controllers\Auth\ForgetController;
use TwoFactorAuth\Http\Controllers\Auth\LoginController;
use TwoFactorAuth\Http\Controllers\Auth\RegisterController;
use TwoFactorAuth\Http\Controllers\Profile\ProfileController;

Route::namespace("Auth")->group(function () {
    Route::post('/register', [RegisterController::class, 'register'])
        ->middleware(config('two-factor-config.throttler_middleware'));

    Route::post('/verify', [RegisterController::class, 'verify']);

    Route::post('/login', [LoginController::class, 'login']);

    Route::post('/forgetPassword', [ForgetController::class, 'forget']);
    Route::post('/verifyForget', [ForgetController::class, 'verifyForget']);

});

Route::middleware('auth:api')->prefix('profile')->namespace("Profile")->group(function () {


    Route::post('/', [ProfileController::class, 'profile']);
    Route::post('/changePassword', [ProfileController::class, 'changePassword']);
    Route::post('/update', [ProfileController::class, 'updateProfile']);
});
