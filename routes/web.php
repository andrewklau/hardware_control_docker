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

Route::get('/jobs', 'JobController@index');
Route::get('/jobs/view/{job}', 'JobController@view');
Route::get('/jobs/new', 'JobController@newJob');
Route::post('/jobs/new', 'JobController@postJob');
