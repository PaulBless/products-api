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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/products/create', 'HomeController@create')->name('create');
Route::post('/store', 'HomeController@store')->name('home.store');
Route::put('/products/edit/{id}', 'HomeController@update')->name('update');
Route::delete('/products/delete/{id}', 'HomeController@destroy')->name('destroy');
