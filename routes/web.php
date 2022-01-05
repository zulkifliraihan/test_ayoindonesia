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

    Route::get('/', 'Dashboard\HomeController@index')->name('index');
    Route::resource('teams', 'Dashboard\TeamsController');
    Route::resource('players', 'Dashboard\PlayersController');
});
