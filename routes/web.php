<?php

use Illuminate\Support\Facades\Route;

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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('kota/{id}', 'HomeController@getKota');



// Dashboard
Route::group(['prefix' => 'admin',  'middleware' => ['auth'], 'as' => 'admin.'], function(){

    // Route::get('/', 'Dashboard\HomeController@index')->name('index');

    Route::resources([
        'teams' => 'Dashboard\TeamsController',
        'players' => 'Dashboard\PlayersController',
        'matchs' => 'Dashboard\MatchsController',
    ], [
        'except' => ['show']
    ]);

    Route::get('matchs/{matchId}/report', 'Dashboard\ReportsController@index')->name('matchs.report.index');
    Route::get('matchs/{matchId}/report/create', 'Dashboard\ReportsController@create')->name('matchs.report.create');
    Route::post('matchs/{matchId}/report/store', 'Dashboard\ReportsController@store')->name('matchs.report.store');
    Route::get('matchs/{matchId}/report/edit', 'Dashboard\ReportsController@edit')->name('matchs.report.edit');
    Route::put('matchs/{matchId}/report/update', 'Dashboard\ReportsController@update')->name('matchs.report.update');
    Route::delete('matchs/{matchId}/report/destroy', 'Dashboard\ReportsController@destroy')->name('matchs.report.destroy');

});
