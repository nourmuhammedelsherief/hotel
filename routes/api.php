<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Site\HomeController;
use \App\Http\Controllers\Api\Site\HotelInformationController;
use \App\Http\Controllers\Api\Site\HotelOurServiceController;
use \App\Http\Controllers\Api\Site\HotelNearServiceController;

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
Route::group(['middleware' => ['cors', 'localization']], function () {
    Route::controller(HomeController::class)->group(function (){
        Route::get('/{subdomain}' , 'index');
        Route::get('/{subdomain}/sliders' , 'sliders');
        Route::get('/{subdomain}/reservations' , 'reservations');
        Route::get('/{subdomain}/locations' , 'locations');
        Route::get('/{subdomain}/gallery' , 'gallery');
        Route::get('/{subdomain}/gallery_categories' , 'gallery_categories');
        Route::get('/{subdomain}/category/{id}/galleries' , 'photos');
        Route::get('/{subdomain}/rate_branches' , 'rate_branches');
        Route::post('/{subdomain}/rate_hotel' , 'rate_hotel');
        Route::get('/{subdomain}/contact_us' , 'contact_us');
        Route::get('/{subdomain}/pixel_codes' , 'pixel_codes');
        Route::get('/{subdomain}/hotel_colors' , 'hotel_colors');
    });
    Route::controller(HotelInformationController::class)->group(function (){
        Route::get('/{subdomain}/information_about_us' , 'information_about_us');
        Route::get('/{subdomain}/information_categories' , 'information_categories');
        Route::get('/{subdomain}/information_category_items/{id}' , 'information_category_items');
        Route::get('/{subdomain}/show_information_category_item/{id}' , 'information_category_item');
    });
    Route::controller(HotelOurServiceController::class)->group(function (){
        Route::get('/{subdomain}/our_services' , 'our_services');
        Route::get('/{subdomain}/our_services_categories' , 'our_services_categories');
        Route::get('/{subdomain}/our_services_category_items/{id}' , 'our_services_category_items');
        Route::get('/{subdomain}/show_our_services_category_item/{id}' , 'show_our_services_category_item');
    });
    Route::controller(HotelNearServiceController::class)->group(function (){
        Route::get('/{subdomain}/near_services' , 'near_services');
        Route::get('/{subdomain}/near_services_categories' , 'near_services_categories');
        Route::get('/{subdomain}/near_services_category_items/{id}' , 'near_services_category_items');
        Route::get('/{subdomain}/show_near_services_category_item/{id}' , 'show_near_services_category_item');
    });
});
