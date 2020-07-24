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
    return view('welcome', [
        'markets' => App\Markets::all()
    ]);
});


// Route for index and individual market pages
Route::get('/markets', 'MarketsController@index');
Route::post('/markets', 'MarketsController@store');
Route::get('/markets/create', 'MarketsController@create');
Route::get('/markets/{market}', 'MarketsController@show')->name('markets.show');
Route::get('/markets/{market}/edit', 'MarketsController@edit');
Route::put('/markets/{market}', 'MarketsController@update');

// Route for products index and individual pages
Route::get('/products', 'ProductsController@index');
Route::post('/products', 'ProductsController@store');
Route::get('/products/create', 'ProductsController@create');
Route::get('/products/{product}', 'ProductsController@show')->name('products.show');
Route::get('/products/{product}/edit', 'ProductsController@edit');
Route::put('/products/{product}', 'ProductsController@update');
