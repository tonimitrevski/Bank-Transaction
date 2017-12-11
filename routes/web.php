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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/deposit', 'HomeController@deposit')->name('deposit');
Route::post('/withdraw', 'HomeController@withdraw')->name('withdraw');
Route::get('/reporting/{days?}', 'TransactionController@index')->name('reporting');
