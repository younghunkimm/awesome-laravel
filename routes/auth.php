<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Auth\ResgisterController::class)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/register', 'showRegistrationForm')
            ->name('register');

        Route::post('/register', 'register');
    });
});

Route::controller(\App\Http\Controllers\Auth\EmailVerificationController::class)->group(function() {
    Route::name('verification.')->prefix('/email')->group(function() {
        Route::middleware('auth')->group(function() {
            Route::get('/verify', 'notice')
                ->name('notice');

            Route::get('/verify/{id}/{hash}', 'verify')
                ->middleware('signed')
                ->name('verify');

            Route::post('verification-notification', 'send')
                ->name('send');
        });
    });
});

Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/login', 'showLoginForm')
            ->name('login');
        
        Route::post('/login', 'login');
    });

    Route::post('/logout', 'logout')
        ->name('logout')
        ->middleware('auth');
});

Route::controller(\App\Http\Controllers\Auth\SocialLoginController::class)->group(function() {
    Route::middleware('guest')->name('login.')->group(function() {
        Route::get('/login/{provider}', 'redirect')
            ->name('social');

        Route::get('/login/{provider}/callback', 'callback')
            ->name('social.callback');
    });
});

Route::controller(\App\Http\Controllers\Auth\PasswordResetController::class)->group(function() {
    Route::middleware('guest')->name('password.')->group(function() {
        Route::get('/forgot-password', 'request')
            ->name('request');

        Route::post('/forgot-password', 'email')
            ->name('email');

        Route::get('/reset-password/{token}', 'reset')
            ->name('reset');

        Route::post('/reset-password', 'update')
            ->name('update');
    });
});