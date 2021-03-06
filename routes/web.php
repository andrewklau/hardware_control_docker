<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/* Authenticated users */
Route::group(['middleware' => 'auth'], function () {
    // Devices
    Route::get('/devices', 'DevicesController@index');
    Route::get('/devices/new', 'DevicesController@newDevice');
    Route::post('/devices/new', 'DevicesController@postDevice');
    Route::get('/devices/edit/{device}', 'DevicesController@editDevice');
    Route::post('/devices/edit/{device}', 'DevicesController@patchDevice');

    // Permissions
    Route::get('/permissions', 'PermissionsController@index');
    Route::get('/permissions/new', 'PermissionsController@newPermission');
    Route::post('/permissions/new', 'PermissionsController@postPermission');
    Route::get('/permissions/edit/{permission}', 'PermissionsController@editPermission');
    Route::post('/permissions/edit/{permission}', 'PermissionsController@patchPermission');

    // Jobs
    Route::get('/jobs', 'JobController@index');
    Route::get('/jobs/view/{job}', 'JobController@view');
    Route::get('/jobs/new', 'JobController@newJob');
    Route::post('/jobs/new', 'JobController@postJob');
});
