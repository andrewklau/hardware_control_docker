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
    // Device list
    Route::get('/devices', 'DevicesController@index');
    Route::get('/devices/new', 'DevicesController@newPermission');
    Route::post('/devices/new', 'DevicesController@postPermission');
    Route::get('/devices/edit/{device}', 'DevicesController@editPermission');
    Route::post('/devices/edit/{device}', 'DevicesController@patchPermission');

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
