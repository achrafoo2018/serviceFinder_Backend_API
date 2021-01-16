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

//post
Route::post('posts/create','Api\PostsController@create');
Route::post('posts/delete','Api\PostsController@delete');
Route::post('posts/update','Api\PostsController@update');
Route::get('posts/getPost','Api\PostsController@getPost');
Route::get('posts','Api\PostsController@posts');
Route::get('posts/myPosts','Api\PostsController@myPosts');
Route::get('posts/getSpecialities','Api\PostsController@getSpecialities');
Route::get('posts/getUsedSpecialities','Api\PostsController@getUsedSpecialities');
Route::post('posts/createComment', 'Api\PostsController@createComment');
Route::post('posts/commentsOnPost', 'Api\PostsController@getCommentsOnPost');
Route::post('posts/editComment', 'Api\UserController@editComment');
Route::get('posts/deleteComment', 'Api\UserController@deleteComment');

Route::get('posts/getPost','Api\PostsController@getPost');




//User profile related routes

Route::get('commentsOnMyAccount', 'Api\UserController@getCommentsOnUserProfile')->name("commentsOnMyAccount");
Route::post('createComment', 'Api\UserController@createComment')->name("createComment");


// User settings Routes
Route::post('changePassword', 'Api\UserController@changePassword')->name("changePassword");
Route::post('updateAccount', 'Api\UserController@updateAccount')->name("updateAccount");
Route::post('updateProfile', 'Api\UserController@updateProfile')->name("updateProfile");
