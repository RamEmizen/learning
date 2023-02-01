<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\QrCodeController;
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
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


// Route::get('place-add','PlaceController@addPlace')->name('add.place');
// Route::get('place-list','PlaceController@listPlace')->name('list.place');
// Route::get('/reset-password/{token}', function ($token) {
//     $url = URL::to('/');
//     return view('reset-password',compact('url','token'));
// })->name('reset.password.get');

//api reset password
Route::get('reset-password/{token}',[App\Http\Controllers\UserController::class,'resetPassword'])->name('reset.password.get');
Route::post('change-password',[App\Http\Controllers\UserController::class,'updatePassword'])->name('change.password');

// admin forget password
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::group(['middleware'=>'auth:web'], function() {
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard'); 
Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode');
Route::get('user/list', [App\Http\Controllers\UserController::class, 'userList'])->name('user.list');
Route::get('user/add',[App\Http\Controllers\UserController::class,'addUser'])->name('add.user');
Route::post('user/store',[App\Http\Controllers\UserController::class,'storeUser'])->name('store.user');
Route::get('user/edit/{id}',[App\Http\Controllers\UserController::class,'userEdit'])->name('user.edit');
Route::post('user/update' ,[App\Http\Controllers\UserController::class,'userUpdate'])->name('user.update');
Route::post('user/delete' ,[App\Http\Controllers\UserController::class,'userDelete'])->name('user.delete');
route::get('user/show/{id}',[App\Http\Controllers\UserController::class,'userShow'])->name('user.show');

//roll for user
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
    
});
