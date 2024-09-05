<?php

//use App\Http\Controllers\Categories\AnimalCategorieController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashAuth\AuthAdminController;

use App\Http\Controllers\Breeder\Auth_BreederController;
use App\Http\Controllers\Animal\AnimalCategorieController;


use App\Http\Controllers\Application\Order\OrderController;
use App\Http\Controllers\Application\Feed\App_FeedController;
use App\Http\Controllers\Dashboard\Feeds\Dash_FeedController;

use App\Http\Controllers\Dashboard\order\Dash_OrderController;
use App\Http\Controllers\Dashboard\Location\LocationController;
use App\Http\Controllers\Application\App_BreederController;
use App\Http\Controllers\Dashboard\Pharmacy\PharmacyController;
use App\Http\Controllers\Application\App_VeterinarianController;
use App\Http\Controllers\Application\Cart\App_AddToCartController;
use App\Http\Controllers\Veterinarian\Auth_VeterinarianController;
use App\Http\Controllers\Application\Mesaages\App_MessageController;
use App\Http\Controllers\Dashboard\Diseases\Dash_DiseasesController;
use App\Http\Controllers\Application\Diseases\App_DiseasesController;
use App\Http\Controllers\Application\Pharmacy\App_PharmacyController;
use App\Http\Controllers\Dashboard\Medicines\Dash_MedicineController;
use App\Http\Controllers\Application\Medicines\App_MedicineController;
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


//--------------------section Daashboard--------------------------------
Route::group(['prefix' => 'dash'], function () {

    Route::controller(AuthAdminController::class)->group(function () {

        Route::Post('login-admin','login_admin')->name('dash.login_admin');
        Route::Post('auth/refresh-admin', 'refresh_admin')->name('dash.auth.refresh');
        Route::group(['middleware' => ['auth:admin']], function () {


            //logout Admin
            Route::Post('auth/logout_admin', 'logout_admin')->name('dash.auth.logout');
        });
    });
    Route::group(['middleware' => ['auth:admin']], function () {

        Route::group(['middleware' => ['role:admin']], function () {
            //crud
            Route::controller(Dash_VeterinariansController::class)->group(function () {

                //get all
                Route::get('get-veterinarians','get_veterinarians')->name('dash.get_veterinarians');
                //show doctor
                Route::get('get-veterinarian/{veterinarian}','get_veterinarian')->name('dash.get_veterinarian');
                //delete doctor
                Route::Delete('delete-veterinarian/{veterinarian}','delete_veterinarian')->name('dash.delete_veterinarian');

             });
             //----crud category---------------
             Route::controller(AnimalCategorieController::class)->group(function () {
                Route::post('add/animal_categorey', 'add_categorey')->name('add_categorey');
                Route::post('Edit/animal_categorey/{id}', 'update_categorey')->name('update_categorey');
                Route::get('get/animal_categorey', 'get_categories')->name('get_categories');
                Route::delete('delete/animal_categorey/{id}', 'delete_categories')->name('delete_categories');

           });
           //--------section medicines in dash------
           Route::controller(Dash_MedicineController::class)->group(function () {
              //all medicines
              Route::get('medicines/get-medicines', 'get_medicines')->name('dash.get_medicines');
              //show single medicine
              Route::get('medicines/get-medicine/{medicine}', 'get_medicine')->name('dash.get_medicine');
            //add medicine
            Route::post('medicines/add-medicine', 'add_medicine')->name('add_medicine');
           //update medicine
           Route::PUT('medicines/update-medicine/{medicine}', 'update_medicine')->name('dash.update_medicine');
        //delete
        Route::Delete('medicines/delete-medicine/{medicine}', 'delete_medicine')->name('dash.delete_medicine');


        });
///feed
        Route::controller(Dash_FeedController::class)->group(function () {
            //all feeds
            Route::get('feeds/get-feeds', 'get_feeds')->name('dash.get_feeds');
            //show single medicine
            Route::get('feeds/get-feed/{feed}', 'get_feed')->name('dash.get_feed');
          //add feed
          Route::post('feeds/add-feed', 'add_Feed')->name('add_feed');
         //update feed
         Route::PUT('feeds/update-feed/{feed}', 'update_Feed')->name('dash.update_feed');
      //delete
      Route::Delete('feeds/delete-feed/{feed}', 'delete_feed')->name('dash.delete_feed');


      });




        //-----------------section pharmacy------------------
        Route::controller(PharmacyController::class)->group(function () {
            //add
            Route::post('pharmacies/add-pharmacy', 'add_pharmacy')->name('add_pharmacy');
           //get all
           Route::get('pharmacies/get-pharmacies', 'get_pharmacies')->name('get_pharmacies');
           //show
           Route::get('pharmacies/get-pharmacy/{pharmacy}', 'get_pharmacy')->name('get_pharmacy');
            //update
            Route::put('pharmacies/update-pharmacy/{pharmacy}', 'update_pharmacy')->name('update_pharmacy');
            //delete
            Route::Delete('pharmacies/delete-pharmacy/{pharmacy}', 'delete_pharmacy')->name('delete_pharmacy');
           //add medicineprice to pharmacy
            Route::Post('add-Medicin-To-Pharmacy/{pharmacy}/{medicine}', 'addPriceMedicinToPharmacy')->name('addMedicinToPharmacy');

        });

        });
           //--------section Diseases in dash------

            Route::controller(Dash_DiseasesController::class)->group(function () {
                Route::get('get_diseases', 'get_diseases')->name('dash.get_diseases');
                Route::get('get_disease/{disease}', 'get_disease')->name('dash.get_disease');
                Route::post('add_disease', 'add_disease')->name('dash.add_disease');
                Route::post('update_disease/{id}', 'update_disease')->name('dash.update_disease');
                Route::Delete('delete_disease/{disease}', 'delete_disease')->name('dash.delete_disease');


            });

///location
Route::controller(LocationController::class)->group(function () {

    //get all
    Route::get('get-locations','get_locations')->name('dash.get_locations');
    Route::post('add-location','add_location')->name('dash.add_location');

 });

//orders
Route::controller(Dash_OrderController::class)->group(function () {

    //get all
    Route::get('get-orders','get_orders')->name('dash.orders');
    Route::PUT('edit-status-order/{order}','edit_status_orders')->name('dash.edit_order');

 });



    });
});
//--------------------------------End DashBoaard------------------------
//------------------------------------auth veterinarian--------------------------
Route::group(['prefix' => 'auth'], function () {

    Route::controller(Auth_VeterinarianController::class)->group(function () {
        Route::post('register-veterinarian', 'register_veterinarian')->name('auth.register_veterinarian');
       // Route::post('auth/login-veterinarian', 'login_veterinarian')->name('auth.login_veterinarian');
        Route::post('login', 'login')->name('auth.login');


        // Refresh auth Token
        Route::Post('veterinarian-refresh', 'refresh')->name('veterinarian.refresh');

        Route::group(['middleware' => ['auth:veterinarian']], function () {
            //logout
            Route::Post('auth/logout-veterinarian', 'logout_veterinarian')->name('auth.logout');
        });




    });
});

//-----------------------------------Start section application Front-----------------------------------
Route::group(['prefix' => 'app'], function () {
     //section veterinarian

     Route::controller(App_VeterinarianController::class)->group(function () {
//gat all
        Route::get('get-veterinarians','get_veterinarians')->name('app.get_veterinarians');
       //get single
        Route::get('get-veterinarian/{veterinarian}','get_veterinarian')->name('app.get_veterinarian');

     });

     Route::controller(LocationController::class)->group(function () {

        //get all
        Route::get('get-locations','get_locations')->name('app.get_locations');});


 //section Breeder
     Route::controller(App_BreederController::class)->group(function () {
        //gat all
                Route::get('get-Breeders','get_Breeders')->name('app.get_Breeders');
               //get single
                Route::get('get-Breeder/{Breeder}','get_Breeder')->name('app.get_Breeder');

             });



    Route::controller(App_DiseasesController::class)->group(function () {
        Route::get('get_diseases', 'get_diseases')->name('app.get_diseases');
        Route::get('get_disease/{disease}', 'get_disease')->name('app.get_disease');


    });




     //----------------section Pharmacy----------------
     Route::controller(App_PharmacyController::class)->group(function () {
        //all pharmacies
        Route::get('pharmacies/get-pharmacies', 'get_pharmacies')->name('app.get_pharmacies');
        //show
        Route::get('pharmacies/get-pharmacy/{pharmacy}', 'get_pharmacy')->name('app.get_pharmacy');

             });

             //-----------section medicine----------------------
             Route::controller(App_MedicineController::class)->group(function () {
                //all medicines
                Route::get('medicines/get-medicines', 'get_medicines')->name('app.get_medicines');
                //show single medicine
                Route::get('medicines/get-medicine/{medicine}', 'get_medicine')->name('app.get_medicine');

                     });
                     //--------------------------
                     //feed
                     Route::controller(App_FeedController::class)->group(function () {
                        //all feeds
                        Route::get('feeds/get-feeds', 'get_feeds')->name('app.get_feeds');
                        //show single medicine
                        Route::get('feeds/get-feed/{feed}', 'get_feed')->name('app.get_feed');

                  });


                     //---------------------------

                     //chat
                     Route::group(['middleware' => ['auth:breeder,veterinarian']], function () {

                        Route::group(['middleware' => ['role:breeder|veterinarian']], function () {
                            Route::controller(App_MessageController::class)->group(function () {
                            Route::post('send-message/{receiver_id}', 'send_message');
                            Route::get('get-messages/{conversation}', 'show_messages');
                            Route::get('get-message/{id}', 'show_message');



                            });
                        });

});
});


//-----------------------------Auth breeder-----------------------------------------
Route::group(['prefix' => 'breeder'], function () {

    Route::controller(Auth_BreederController::class)->group(function () {
        Route::post('auth/register-breeder', 'register_breeder')->name('auth.register_breeder');
        Route::post('auth/login-breeder', 'login_breeder')->name('auth.login_breeder');

        // Refresh auth Token

        Route::group(['middleware' => ['auth:breeder']], function () {


            //logout
            Route::Post('auth/logout-breeder', 'logout_breeder')->name('auth.logout');
            ///Add To Cart
            Route::Post('add-to-cart/{item_id}', 'AddToCart')->name('add.cart');

        });


    });


    //cart
    Route::controller(App_AddToCartController::class)->group(function () {

        Route::group(['middleware' => ['auth:breeder,veterinarian']], function () {


                  ///Add To Cart
            Route::Post('add-to-cart/{item_id}', 'AddToCart')->name('add.cart');
            Route::get('get-my-cart', 'get_items_cart')->name('get.cart');
            Route::Delete('delete-item/{item_id}', 'delete_item')->name('delete.cart');
            Route::Delete('clear', 'clear')->name('clear.cart');

        });
    });
    //order
    Route::controller(OrderController::class)->group(function () {

        Route::group(['middleware' => ['auth:breeder,veterinarian']], function () {


                  ///confirm order
            Route::Post('order', 'confirmOrder')->name('add.order');
//get my order
Route::get('get-my-order', 'getmyorder')->name('get.order');

        });
    });
});














