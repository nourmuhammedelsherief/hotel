<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\HotelController\AuthHotelController;
use \App\Http\Controllers\Api\HotelController\HotelController;
use \App\Http\Controllers\Api\HotelController\SubscriptionController;
use \App\Http\Controllers\Api\HotelController\SliderController;
use \App\Http\Controllers\Api\HotelController\ReservationController;
use \App\Http\Controllers\Api\HotelController\LocationController;
use \App\Http\Controllers\Api\HotelController\GalleryCategoryController;
use \App\Http\Controllers\Api\HotelController\GalleryController;
use \App\Http\Controllers\Api\HotelController\HotelRateBranchController;
use \App\Http\Controllers\Api\HotelController\HotelContactController;
use \App\Http\Controllers\Api\HotelController\HotelInformationController;
use \App\Http\Controllers\Api\HotelController\HotelInfoCategoryController;
use \App\Http\Controllers\Api\HotelController\HotelInfoCategoryItemController;
use \App\Http\Controllers\Api\HotelController\HotelServiceController;
use \App\Http\Controllers\Api\HotelController\HotelServiceCategoryController;
use \App\Http\Controllers\Api\HotelController\HotelServiceCategoryItemController;
use \App\Http\Controllers\Api\HotelController\HotelNearServiceController;
use \App\Http\Controllers\Api\HotelController\HotelNearServiceCategoryController;
use \App\Http\Controllers\Api\HotelController\HotelNearServiceCategoryItemController;
use \App\Http\Controllers\Api\HotelController\HotelPixelController;
// hotel controllers

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

Route::get('/check-hotel-status/{id1?}/{id2?}', [SubscriptionController::class , 'check_status'])->name('checkHotelStatus');

Route::middleware(['cors', 'localization'])->group(function () {
    Route::controller(AuthHotelController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/resend_code', 'resend_code');
        Route::post('/verify_phone_number', 'verify_phone');
    });
    Route::controller(HotelController::class)->group(function () {
        Route::get('/countries' , 'countries');
        Route::get('/cities/{id}' , 'cities');
        Route::get('/admin_support_numbers' , 'admin_support_numbers');
    });
});
Route::group(['middleware' => ['auth:hotel-api', 'cors', 'localization']], function () {
    Route::controller(HotelController::class)->group(function () {
        Route::get('/banks' , 'banks');
    });
    Route::prefix('dashboard')->group(function () {
        Route::controller(HotelController::class)->group(function () {
            Route::get('/hotel_control_panel_home', 'hotel_control_home');
            Route::get('/profile', 'profile');
            Route::get('/barcode_url', 'barcode');
            Route::post('/change_password', 'changePassword');
            Route::post('/edit_account', 'edit_account');
            Route::post('/logout', 'logout');
        });

        Route::controller(SubscriptionController::class)->group(function (){
            Route::get('subscribe_price' , 'subscribe_price');
            Route::post('subscribe' , 'store_subscription');
        });
        Route::controller(SliderController::class)->group(function (){
            Route::get('/sliders' , 'index');
            Route::post('/sliders/create' , 'create');
            Route::post('/sliders/{id}/edit' , 'edit');
            Route::get('/sliders/{id}/show' , 'show');
            Route::get('/sliders/{id}/delete' , 'destroy');
            Route::get('/sliders/images/{id}/delete' , 'delete_slider_image');
        });
        Route::controller(ReservationController::class)->group(function (){
            Route::get('/reservations' , 'index');
            Route::post('/reservations/create' , 'create');
            Route::post('/reservations/{id}/edit' , 'edit');
            Route::get('/reservations/{id}/show' , 'show');
            Route::get('/reservations/{id}/delete' , 'destroy');
        });
        Route::controller(LocationController::class)->group(function (){
            Route::get('/locations' , 'index');
            Route::post('/locations/create' , 'create');
            Route::post('/locations/{id}/edit' , 'edit');
            Route::get('/locations/{id}/show' , 'show');
            Route::get('/locations/{id}/delete' , 'destroy');
        });
        Route::controller(GalleryCategoryController::class)->group(function (){
            Route::get('/gallery/categories' , 'index');
            Route::post('/gallery/categories/create' , 'create');
            Route::post('/gallery/categories/{id}/edit' , 'edit');
            Route::get('/gallery/categories/{id}/show' , 'show');
            Route::get('/gallery/categories/{id}/delete' , 'destroy');
        });
        Route::controller(GalleryController::class)->group(function (){
            Route::get('/get_hotel_gallery_info' , 'get_hotel_gallery_info');
            Route::post('/edit_hotel_gallery_info' , 'edit_hotel_gallery_info');
            Route::get('/galleries' , 'index');
            Route::post('/galleries/create' , 'create');
            Route::post('/galleries/{id}/edit' , 'edit');
            Route::get('/galleries/{id}/show' , 'show');
            Route::get('/galleries/{id}/delete' , 'destroy');
        });
        Route::controller(HotelRateBranchController::class)->group(function (){
            Route::get('/rate_branches' , 'index');
            Route::post('/rate_branches/create' , 'create');
            Route::post('/rate_branches/{id}/edit' , 'edit');
            Route::get('/rate_branches/{id}/show' , 'show');
            Route::get('/rate_branches/{id}/delete' , 'destroy');
            Route::get('/rates' , 'rates');
            Route::get('/rates/{id}/delete' , 'destroy_rate');
        });
        Route::controller(HotelContactController::class)->group(function (){
            Route::get('/contact_info' , 'show');
            Route::post('/contact_info/{id}/edit' , 'edit');
        });
        // hotel information routes
        Route::controller(HotelInformationController::class)->group(function (){
            Route::get('/information_about_us' , 'show');
            Route::post('/information_about_us/edit' , 'edit');
        });
        Route::controller(HotelInfoCategoryController::class)->group(function (){
            Route::get('/information_categories' , 'index');
            Route::post('/information_categories/create' , 'create');
            Route::post('/information_categories/{id}/edit' , 'edit');
            Route::get('/information_categories/{id}/show' , 'show');
            Route::get('/information_categories/{id}/delete' , 'destroy');
        });
        Route::controller(HotelInfoCategoryItemController::class)->group(function (){
            Route::get('/information_categories_items/{id}' , 'index');
            Route::post('/information_categories_items/{id}/create' , 'create');
            Route::post('/information_categories_items/{id}/edit' , 'edit');
            Route::get('/information_categories_items/{id}/show' , 'show');
            Route::get('/information_categories_items/{id}/delete' , 'destroy');
            Route::get('/remove_item_slider_photo/{id}/delete' , 'remove_item_slider_photo');
        });
        // hotel services routes
        Route::controller(HotelServiceController::class)->group(function (){
            Route::get('/our_services' , 'show');
            Route::post('/our_services/edit' , 'edit');
        });
        Route::controller(HotelServiceCategoryController::class)->group(function (){
            Route::get('/service_categories' , 'index');
            Route::post('/service_categories/create' , 'create');
            Route::post('/service_categories/{id}/edit' , 'edit');
            Route::get('/service_categories/{id}/show' , 'show');
            Route::get('/service_categories/{id}/delete' , 'destroy');
        });
        Route::controller(HotelServiceCategoryItemController::class)->group(function (){
            Route::get('/service_category_items/{id}' , 'index');
            Route::post('/service_category_items/{id}/create' , 'create');
            Route::post('/service_category_items/{id}/edit' , 'edit');
            Route::get('/service_category_items/{id}/show' , 'show');
            Route::get('/service_category_items/{id}/delete' , 'destroy');
            Route::get('/remove_service_item_photo/{id}/delete' , 'remove_service_item_photo');
        });

        // hotel near services routes
        Route::controller(HotelNearServiceController::class)->group(function (){
            Route::get('/near_services' , 'show');
            Route::post('/near_services/edit' , 'edit');
        });
        Route::controller(HotelNearServiceCategoryController::class)->group(function (){
            Route::get('/near_service_categories' , 'index');
            Route::post('/near_service_categories/create' , 'create');
            Route::post('/near_service_categories/{id}/edit' , 'edit');
            Route::get('/near_service_categories/{id}/show' , 'show');
            Route::get('/near_service_categories/{id}/delete' , 'destroy');
        });
        Route::controller(HotelNearServiceCategoryItemController::class)->group(function (){
            Route::get('/near_service_category_items/{id}' , 'index');
            Route::post('/near_service_category_items/{id}/create' , 'create');
            Route::post('/near_service_category_items/{id}/edit' , 'edit');
            Route::get('/near_service_category_items/{id}/show' , 'show');
            Route::get('/near_service_category_items/{id}/delete' , 'destroy');
            Route::get('/remove_near_item_photo/{id}/delete' , 'remove_near_item_photo');
        });
        // hotel pixel routes
        Route::controller(HotelPixelController::class)->group(function (){
            Route::get('/pixel_codes' , 'index');
            Route::post('/pixel_codes/create' , 'create');
            Route::post('/pixel_codes/{id}/edit' , 'edit');
            Route::get('/pixel_codes/{id}/show' , 'show');
            Route::get('/pixel_codes/{id}/delete' , 'destroy');
        });
    });
});

//1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq
