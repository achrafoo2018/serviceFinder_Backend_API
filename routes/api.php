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


//Auth Routes
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');
Route::get('logout', 'Api\AuthController@logout');


// Password reset Routes
Route::post('sendPasswordResetLink', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('resetPassword', 'Api\ResetPasswordController@reset');


// User settings Routes
Route::post('changePassword', 'Api\UserController@changePassword')->name("changePassword");
Route::post('updateAccount', 'Api\UserController@updateAccount')->name("updateAccount");
Route::post('updateProfile', 'Api\UserController@updateProfile')->name("updateProfile");