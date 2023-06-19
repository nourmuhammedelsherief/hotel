<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Site\HomeController;

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

Route::domain('{account}.localhost')->group(function () {
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
            Route::get('/ss' , 'ss');
        });
    });
});
