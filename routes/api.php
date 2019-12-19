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
        Route::get('users/projects/{user}', 'UserController@projectsByUser');
        Route::put('users/status/{id}', 'UserController@activateDeactivate');
        Route::resource('clients', 'ClientController');
    });
    Route::group(['middleware' => ['role:admin|publisher']], function () {
        Route::resource('projects', 'ProjectController');
        Route::get('projects/users/{project}', 'ProjectController@usersByProject');
        Route::post('projects/users', 'ProjectController@createUserProject');
        Route::delete('projects/users/{user}', 'ProjectController@deleteUserProject');
        Route::post('pictures', 'ProjectController@uploadPicture');
        Route::post('tags/project', 'TagController@addTagProject');
        Route::resource('tags', 'TagController');
    });
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('roles', 'AdminController@roles');
        Route::post('roles', 'AdminController@createRole');
        Route::get('permissions', 'AdminController@permissions');
        Route::post('permissions', 'AdminController@createPermission');
        Route::post('assign/permissions', 'AdminController@assignpermission');
        Route::resource('history', 'HistoryController');
    });
});
