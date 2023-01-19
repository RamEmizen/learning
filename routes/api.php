<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StripePaymentController;



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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//regstriation api
Route::post('/register',[UserController::class,'registration']);
Route::post('/login',[UserController::class,'login']);
Route::post('get-otp',[UserController::class,'mobileOtp']);
Route::post('get-verifyOtp',[UserController::class,'verifyOtp']);

Route::group(['middleware'=>'auth:api'], function() {
    
Route::post('/update-profile',[UserController::class,'updateProfile']);
Route::get('get-profile', [UserController::class ,'getProfile']);
Route::post('password/email', [UserController::class ,'forgot']);

//agro
Route::post('/agoraToken',[UserController::class,'generate_token']);

//paypal 
Route::post('stripes', [StripePaymentController::class, 'stripes']);
Route::get('stripe-successs', [StripePaymentController::class, 'stripeSuccesss'])->name('stripes.success');
Route::get('stripe-errors', [StripePaymentController::class, 'stripeErrors'])->name('stripes.error');

});

