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

Route::group(['namespace' => 'Web'], function () {
    Route::get('/', 'AppController@getApp')->middleware('auth');

    Route::get('/login', 'Appcontroller@getLogin')->middleware('guest')->name('login');

    Route::get('/auth/{social}', 'AuthenticationController@getSocialRedirect')->middleware('guest');

    Route::get('/auth/{social}/callback', 'AuthenticationController@getSocialCallback')->middleware('guest');
});
