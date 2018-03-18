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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'RegisterController@register');
Route::post('login', 'LoginController@authenticate');

Route::get('topics', 'TopicController@index');
Route::get('topics/{topic}', 'TopicController@show');
Route::post('topics', 'TopicController@create');
Route::match(['PUT', 'PATCH'], 'topics/{topic}', 'TopicController@update');
Route::delete('topics/{topic}', 'TopicController@delete');

Route::get('news', 'NewsController@index');
Route::get('news/{news}', 'NewsController@show');
Route::post('news', 'NewsController@create');
Route::match(['PUT', 'PATCH'], 'news/{news}', 'NewsController@update');
Route::delete('news/{news}', 'NewsController@delete');
