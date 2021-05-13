<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
    return redirect('/login');
});
//Route::post('/licenseservice/getLicenseStatus', 'LicenseController@getLicenseStatus');

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home',[App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', 'App\Http\Controllers\UserController',['only' => ['index' , 'store', 'update', 'destroy']]);
    Route::resource('customers', 'App\Http\Controllers\CustomerController',['only' => ['index' , 'store', 'update', 'destroy']]);
    Route::resource('system-licenses', 'App\Http\Controllers\LicenseController', ['only' => ['index' , 'store', 'update']]);
    Route::post('all-licenses', 'App\Http\Controllers\LicenseController@getData')->name('all_licenses');

    Route::get('lang/{locale}', 'App\Http\Controllers\LocalizationController@index');
    Route::post('/change-password', 'App\Http\Controllers\UserController@changePassword')->name('change_password');
    Route::get('/generate-license/{id}', 'App\Http\Controllers\LicenseController@generateLicense')->name('generate_license');
    Route::post('/identity-upload/{id}', 'App\Http\Controllers\LicenseController@identityUpload')->name('identity_upload');
});

//Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
