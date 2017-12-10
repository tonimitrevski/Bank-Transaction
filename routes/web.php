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
    $currentDate = \Carbon\Carbon::now();

    /*$test = \DB::table('transactions')
        ->select(
            [
                'country_id',
                \DB::raw('COUNT(DISTINCT user_id) AS Unique_Customers'),
                \DB::raw('COUNT(CASE WHEN type = 1 THEN id END) AS No_of_Deposits'),
                \DB::raw('SUM(CASE WHEN type = 1 THEN amount END) AS deposit'),
                \DB::raw('COUNT(CASE WHEN type = 0 THEN id END) AS No_of_withdraw'),
                \DB::raw('SUM(CASE WHEN type = 0 THEN amount END) AS withdraw'),
            ]
        )
        ->where('created_at', '>', $currentDate->subWeek())
        ->groupBy('country_id')
        ->toSQL();


    dd($test);*/
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
