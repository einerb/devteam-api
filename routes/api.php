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

Route::post('login', 'UserController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'UserController@logout');
    Route::group(['middleware' => ['role:admin|operator']], function () {
        Route::resource('users', 'UserController');
        Route::get('user', 'UserController@user');
        Route::put('users/status/{id}', 'UserController@activateDeactivate');
    });
    Route::group(['middleware' => ['role:admin|publisher']], function () {
        Route::resource('projects', 'ProjectController');
        Route::post('pictures', 'ProjectController@uploadPicture');
        Route::resource('labels', 'LabelController');
    });
    Route::group(['middleware' => ['role:admin|operator']], function () {
        Route::resource('clients', 'ClientController');
    });
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('admin', 'AdminController');
        Route::resource('history', 'HistoryController');
    });
});
