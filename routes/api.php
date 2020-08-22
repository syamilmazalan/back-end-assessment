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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'Api\AuthController@logout');

    Route::get('users', 'Api\UserController@index');
    Route::get('users/{email}', 'Api\UserController@show');
    Route::patch('users/{email}', 'Api\UserController@update');
    Route::delete('users/{email}', 'Api\UserController@destroy');
});