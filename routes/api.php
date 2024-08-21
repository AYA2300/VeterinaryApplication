<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashAuth\AuthAdminController;
use App\Http\Controllers\Application\App_VeterinarianController;
use App\Http\Controllers\Veterinarian\Auth_VeterinarianController;
use App\Http\Controllers\Dashboard\Veterinarians\Dash_VeterinariansController;

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

// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('logout', 'logout');
//     Route::post('refresh', 'refresh');

// });


//
Route::group(['prefix' => 'dash'], function () {

    Route::controller(AuthAdminController::class)->group(function () {

        Route::Post('login-admin','login_admin')->name('dash.login_admin');

     });
    Route::group(['middleware' => ['auth:admin']], function () {

        Route::group(['middleware' => ['role:admin']], function () {
            Route::controller(Dash_VeterinariansController::class)->group(function () {

                Route::get('get-veterinarians','get_veterinarians')->name('app.get_veterinarians');
                Route::get('get-veterinarian/{veterinarian}','get_veterinarian')->name('app.get_veterinarian');

             });

        });
    });
});
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
//------------------------------------------------//
//app
Route::group(['prefix' => 'app'], function () {
     //section veterinarian

     Route::controller(App_VeterinarianController::class)->group(function () {

        Route::get('get-veterinarians','get_veterinarians')->name('app.get_veterinarians');
        Route::get('get-veterinarian/{veterinarian}','get_veterinarian')->name('app.get_veterinarian');

     });

});
