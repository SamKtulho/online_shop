<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group( function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'signin')
        ->middleware('throttle:auth')
        ->name('signin');

    Route::get('/signup', 'signup')->name('signup');
    Route::post('/signup', 'store')
        ->middleware('throttle:auth')
        ->name('store');

    Route::delete('/logout', 'logout')->name('logout');

    Route::get('/forgot-password', 'forgotPassword')
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', 'passwordEmail')
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', 'passwordReset')
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', 'passwordUpdate')
        ->middleware('guest')
        ->name('password.update');

    Route::get('/auth/github/redirect', 'githubRedirect')->name('github.redirect');

    Route::get('/auth/github/callback', 'githubCallback')->name('github.callback');

});

Route::get('/', HomeController::class)->name('home');


