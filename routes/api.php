<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\AuthController;



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
Route::group(['namespace'=>'App\Http\Controllers'],function(){

    Route::group(['middleware' => ['api'],'prefix' => 'auth'], function () {
    Route::post('/login',  'AuthController@login')->name('login');
    // Route::post('/admin',  'AuthController@adminlogin');
    Route::post('/signup',  'AuthController@signup');
    Route::post('/logout',  'AuthController@logout');
    Route::get('/profile',  'AuthController@profile')->middleware('auth');
    Route::post('/edit/profile',  'AuthController@editProfile');
    Route::get('/users',  'AuthController@users')->middleware('AdminMiddleware');
    Route::post('/users/show',  'AuthController@show_user')->middleware('AdminMiddleware');
    Route::post('/users/delete',  'AuthController@delete_user')->middleware('AdminMiddleware');
    Route::post('/adminlogin', 'AuthController@admin_login')->name('adminlogin');
   // Route::post('/signin', 'AuthController@sign_in');
  
});



Route::group(['middleware' => ['api','auth','AdminMiddleware'],'prefix' => 'category'], function () {
    Route::post('/create',  'CategoryController@create');
    Route::get('/all',  'CategoryController@index');
    Route::post('/update/{id}',  'CategoryController@update');
    Route::post('/delete/{id}',  'CategoryController@destroy');
    Route::get('/show/{id}',  'CategoryController@show');

});
Route::group(['middleware' => 'api','prefix' => 'food'], function () {
    Route::post('/create',  'FoodController@create')->middleware('AdminMiddleware');
    Route::get('/all',  'FoodController@index');
    Route::post('/update/{id}',  'FoodController@update')->middleware('AdminMiddleware');
    Route::post('/delete/{id}',  'FoodController@destroy')->middleware('AdminMiddleware');
    Route::get('/show/{id}',  'FoodController@show');

});

Route::group(['middleware' => 'api','prefix' => 'bookTable'], function () {
    Route::post('/create',  'BookTableController@create');
    Route::get('/all',  'BookTableController@index')->middleware('AdminMiddleware');
    Route::post('/update/{id}',  'BookTableController@update');
    Route::post('/delete/{id}',  'BookTableController@destroy');
    Route::get('/show/{id}',  'BookTableController@show');

});
Route::group(['middleware' => 'api','prefix' => 'order'], function () {
    Route::post('/create',  'OrderController@create_order');
    Route::get('/all',  'OrderController@index')->middleware('AdminMiddleware');
    Route::get('/items/{id}',  'OrderController@order_Items');  
    Route::post('/deleteitems/{id}',  'OrderController@delete_order_Items'); 
    Route::post('/edit-Quantity',  'OrderController@edit_quantity'); 
     
    Route::post('/update/{id}',  'OrderController@update');
    Route::post('/delete/{id}',  'OrderController@destroy');
    Route::get('/show/{id}',  'OrderController@show');

});
});

