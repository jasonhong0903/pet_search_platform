<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\SocialiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['api']], function () {

    Route::get('/{social_site}/auth', [SocialiteController::class, 'redirectToProvider']);

    Route::get('/{social_site}/auth/callback', [SocialiteController::class, 'handleProviderCallback']);

    Route::post('/register', [UserController::class, 'postRegister']);

    Route::group(['prefix' => 'users', 'middleware' => ['token.check']], function () {
        Route::get('/', [UserController::class, 'getUser']);
        Route::put('/', [UserController::class, 'putUser']);
    });
});


