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
        Route::post('/verify_phone_number', 'verify_phone');
    });
});
Route::group(['middleware' => ['auth:hotel-api', 'cors', 'localization']], function () {
    Route::prefix('dashboard')->group(function () {
        Route::controller(HotelController::class)->group(function () {
            Route::get('/profile', 'profile');
            Route::get('/barcode_url', 'barcode');
            Route::post('/change_password', 'changePassword');
            Route::post('/edit_account', 'edit_account');
            Route::post('/logout', 'logout');
        });

        Route::controller(SubscriptionController::class)->group(function (){
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
    });
});
