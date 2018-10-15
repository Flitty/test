<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'api', 'as' => 'api'], function () {
    Route::group(['prefix' => 'auth', 'as' => '.auth'], function () {
        Route::post('login', ['as' => '.login', 'uses' => 'AuthController@login']);
        Route::post('register', ['as' => '.register', 'uses' => 'AuthController@register']);
        Route::group(['middleware' => 'auth:api'], function() {
            Route::post('logout', ['as' => '.logout', 'uses' => 'AuthController@logout']);
            Route::post('refresh', ['as' => '.refresh', 'uses' => 'AuthController@refresh']);
        });
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::group(['prefix' => 'profile', 'as' => '.profile'], function() {
            Route::get('', ['as' => '', 'uses' => 'ProfileController@index']);
            Route::put('update', ['as' => '.update', 'uses' => 'ProfileController@update']);
        });

        Route::group(['prefix' => 'figure'], function() {
            Route::get('index', ['as' => '.index', 'uses' => 'FigureController@index']);
            Route::get('validate-point', ['as' => '.validate-point', 'uses' => 'FigureController@validatePoint']);
            Route::post('store', ['as' => '.store', 'uses' => 'FigureController@store']);
            Route::delete('delete/{figureId}', ['as' => '.delete', 'uses' => 'FigureController@delete']);
            Route::put('update/{figureId}', ['as' => '.update', 'uses' => 'FigureController@update']);
        });
    });



});


