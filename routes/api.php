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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('videos','VideoController@index');

//Route::resource('videos','VideoController@store');


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::post('videos','VideoController@store');
    Route::get('videos/{id}','VideoController@show');
    Route::delete('videos/{id}','VideoController@destroy');
    Route::post('videos/{id}','VideoController@update');
    Route::get('like_video/{id}','VideoController@like_video');
    Route::post('comment_on_video/{id}','VideoController@comment_on_video');

});
Route::get('videos', 'VideoController@index');