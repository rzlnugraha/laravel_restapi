<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    
    Route::get('me', 'AuthController@me')->middleware('api.auth');
    
    Route::group(['middleware' => 'api.auth'], function ($router) {
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::put('update_name', 'AuthController@updateName');
        Route::put('update_password', 'AuthController@updatePassword');
    });
    
});