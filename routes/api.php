<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {
    Route::get('/cafes', 'API\CafesController@getCafes');
    Route::get('/cafes/{id}', 'API\CafesController@getCafe');

    Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');

    Route::get('/tags', 'API\TagsController@getTags');

    Route::get('/user', 'API\UserController@getUser');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::post('/cafes', 'API\CafesController@postNewCafe');

    Route::post('/cafes/{id}/like', 'API\CafesController@postLikeCafe');
    Route::delete('/cafes/{id}/like', 'API\CafesController@deleteLikeCafe');

    Route::post('/cafes/{id}/tags', 'API\CafesController@postAddTags');
    Route::delete('/cafes/{id}/tags/{tagID}', 'API\CafesController@deleteCafeTag');
});
