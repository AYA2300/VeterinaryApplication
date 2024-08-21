<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Veterinarian\Auth_VeterinarianController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


//
Route::group(['prefix' => 'veterinarian'], function () {

    Route::controller(Auth_VeterinarianController::class)->group(function () {
        Route::post('auth/register-veterinarian', 'register_veterinarian')->name('auth.register_veterinarian');
        Route::post('auth/login-veterinarian', 'login_veterinarian')->name('auth.login_veterinarian');

        // Refresh auth Token
        Route::Post('veterinarian-refresh', 'refresh')->name('veterinarian.refresh');

        Route::group(['middleware' => ['auth:veterinarian']], function () {


            //logout
            Route::Post('auth/logout-veterinarian', 'logout_veterinarian')->name('auth.logout');
        });
    });
});
