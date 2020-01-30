<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', 'FacebookController@welcome');
Route::get('/thankyou', 'FacebookController@thankyou');

Route::get('/authenticate/facebook', 'Auth\FacebookController@redirect')->name('auth.facebook.redirect');
Route::get('/authenticate/facebook/callback', 'Auth\FacebookController@callback');
