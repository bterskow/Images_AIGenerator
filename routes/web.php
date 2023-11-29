<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::group(['prefix' => '', 'middleware' => 'redirectIf404'], function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('ifAuth');

    Route::group(['prefix' => 'auth'], function() {
        Route::get('/', [AuthenticatedSessionController::class, 'redirect_to_google'])->name('redirect_to_google');
        Route::get('/google/callback', [AuthenticatedSessionController::class, 'auth_via_google'])->name('authorization');
    });

    // Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

    Route::group(['middleware' => 'auth'], function() {
        Route::group(['prefix' => 'dashboard'], function() {
            Route::get('/', [MainController::class, 'dashboard'])->name('dashboard');
        });
        Route::group(['prefix' => 'album'], function() {
            Route::get('/', [MainController::class, 'album'])->name('album');
            Route::delete('/{index}', [MainController::class, 'delete_image'])->name('delete_image');
        });

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    Route::group(['prefix' => 'users'], function() {
        Route::get('/', [MainController::class, 'users'])->name('users');
    });

    Route::group(['prefix' => 'ai'], function() {
        Route::get('/generate_image', function () {
            return 'You have to write prompt before generate image!';
        });

        Route::get('/generate_image/{prompt}', [MainController::class, 'generate_image'])->name('generate_image');
    });
});

Route::fallback(function () {
    return redirect()->route('login');
});
