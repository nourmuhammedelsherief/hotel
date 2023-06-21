<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Site\HomeController;
use \App\Http\Controllers\Api\Site\HotelInformationController;
use \App\Http\Controllers\Api\Site\HotelOurServiceController;

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

Route::domain('{account}.easyhotelll.com')->group(function () {
    Route::group(['middleware' => ['cors', 'localization']], function () {
        Route::controller(HomeController::class)->group(function (){
            Route::get('/' , 'index');
            Route::get('/sliders' , 'sliders');
            Route::get('/reservations' , 'reservations');
            Route::get('/locations' , 'locations');
            Route::get('/galleries' , 'photos');
            Route::get('/galleries' , 'photos');
            Route::get('/rate_branches' , 'rate_branches');
            Route::post('/rate_hotel' , 'rate_hotel');
            Route::get('/contact_us' , 'contact_us');
        });
        Route::controller(HotelInformationController::class)->group(function (){
            Route::get('/information_about_us' , 'information_about_us');
            Route::get('/information_categories' , 'information_categories');
            Route::get('/information_category_items/{id}' , 'information_category_items');
        });
        Route::controller(HotelOurServiceController::class)->group(function (){
            Route::get('/our_services' , 'our_services');
            Route::get('/our_services_categories' , 'our_services_categories');
            Route::get('/our_services_category_items/{id}' , 'our_services_category_items');
        });
    });
});
