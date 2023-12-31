<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
/**
 * start admin controllers
 */
use \App\Http\Controllers\AdminController\HomeController;
use \App\Http\Controllers\AdminController\Admin\LoginController;
use \App\Http\Controllers\AdminController\Admin\ForgotPasswordController;
use \App\Http\Controllers\AdminController\AdminController;

/**
 * end admin controllers
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/check-hotel-status/{id1?}/{id2?}', [\App\Http\Controllers\Api\HotelController\SubscriptionController::class , 'check_status'])->name('checkHotelStatus');
Route::get('/edfa-check-hotel-status/{id1?}/{id2?}', [\App\Http\Controllers\Api\HotelController\SubscriptionController::class , 'check_edfa_status'])->name('checkEdfaHotelStatus');
Route::get('/error', function (){
    $error = [
        'message' => trans('messages.errorPayment')
    ];
    return ApiController::respondWithErrorObject($error);
});

//Route::domain('{account}.localhost')->group(function () {
//    Route::get('/', function ($account) {
//        $hotel = \App\Models\Hotel::whereSubdomain($account)->firstOrFail();
//        return 'welcome with sub ' . $hotel->name_ar;
//    });
//});
