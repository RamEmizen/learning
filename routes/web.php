<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard'); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::get('place-add','PlaceController@addPlace')->name('add.place');
Route::get('place-list','PlaceController@listPlace')->name('list.place');

Route::get('user/list', [UserController::class, 'userList'])->name('user.list');

// Route::get('/reset-password/{token}', function ($token) {
//     $url = URL::to('/');
//     return view('reset-password',compact('url','token'));
// })->name('reset.password.get');
Route::get('reset-password/{token}',[UserController::class,'resetPassword'])->name('reset.password.get');
Route::post('change-password',[UserController::class,'updatePassword'])->name('change.password');
