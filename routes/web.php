<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Support\Facades\Route;

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

Route::controller(SignInController::class)->group( function () {
    Route::get('/login', 'page')->name('login.page');
    Route::post('/login', 'handle')
        ->middleware('throttle:auth')
        ->name('login.handle');

    Route::delete('/logout', 'logout')->name('logout');
});

Route::controller(SignUpController::class)->group( function () {
    Route::get('/signup', 'page')->name('signup.page');
    Route::post('/signup', 'handle')
        ->middleware('throttle:auth')
        ->name('signup.handle');
});

Route::controller(ForgotPasswordController::class)->group( function () {
    Route::get('/forgot-password', 'page')
        ->middleware('guest')
        ->name('forgot-password.page');

    Route::post('/forgot-password', 'handle')
        ->middleware('guest')
        ->name('forgot-password.handle');
});

Route::controller(ResetPasswordController::class)->group( function () {
    Route::get('/reset-password/{token}', 'page')
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', 'handle')
        ->middleware('guest')
        ->name('password.update');
});

Route::controller(SocialiteController::class)->group( function () {
    Route::get('/auth/{token}/redirect', 'redirect')->name('socialite.redirect');

    Route::get('/auth/{token}/callback', 'callback')->name('socialite.callback');
});

Route::get('/', HomeController::class)->name('home');

Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
    ->where('method', 'resize|crop|scale')
    ->where('size', '\d+x\d+')
    ->where('file', '.+\.(jpg|jpeg|png|gif)$')
    ->name('thumbnail');
