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

Route::get('/', 'App\Http\Controllers\PagesController@index');

Route::group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function() {
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function() {
        Route::get('/', 'DashboardController@dashboard')->name('index');
        Route::get('dashboard','DashboardController@dashboard')->name('dashboard');
    });
});
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::get('/operations/get', 'App\Http\Controllers\OperationController@get')->name('getOperations');
Route::get('/operations/operationConfirm/{id}/get', 'App\Http\Controllers\OperationController@getOperationConfirm')->name('getOperationConfirm');
Route::post('/operations/operationConfirm/{id}/save', 'App\Http\Controllers\OperationController@saveOperationConfirm')->name('saveOperationConfirm');

Route::get('/operations/{id}/update/', 'App\Http\Controllers\OperationController@update')->name('updateOperation');

