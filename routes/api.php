<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** CRUD Routes */
Route::get('/list', 'ProductController@index');
## Get Products Based on Currency Parameter
Route::get('/products/{currency?}','ProductController@productsbycurrency');
Route::get('/editproduct/{id}', 'ProductController@show');
Route::post('/addproduct', 'ProductController@store');
Route::put('/updateproduct/{id}', 'ProductController@update');
Route::delete('/deleteproduct/{id}', 'ProductController@destroy');


