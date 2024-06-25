<?php

use Illuminate\Support\Facades\Route;
use App\market_days;
use App\Markets;


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
Auth::routes();

Route::get('/', 'MarketDaysController@index')
    ->middleware('auth');

// Route for index and individual market pages
Route::get('/markets', 'MarketsController@index')
    ->middleware('auth');
Route::post('/markets', 'MarketsController@store');
Route::get('/markets/create', 'MarketsController@create')
    ->middleware('auth');
Route::get('/markets/{market}', 'MarketsController@show')->name('markets.show')
    ->middleware('auth');
Route::get('/markets/{market}/edit', 'MarketsController@edit')
->middleware('auth');
Route::put('/markets/{market}', 'MarketsController@update');

// Route for products index and individual pages
Route::get('/products', 'ProductsController@index')
    ->middleware('auth');
Route::post('/products', 'ProductsController@store');
Route::get('/products/create', 'ProductsController@create')
    ->middleware('auth');
Route::get('/products/{product}', 'ProductsController@show')->name('products.show')
    ->middleware('auth');
Route::get('/products/{product}/edit', 'ProductsController@edit')
    ->middleware('auth');
// Route::put('/products/{product}', 'ProductsController@update')
// ->middleware('auth');
Route::put('/products/{id}/edit', 'ProductsController@update')
->middleware('auth');
Route::delete('products/{product}', 'ProductsController@destroy');

// Route for categories index and individual (edit) pages
Route::get('/categories', 'CategoriesController@index')
    ->middleware('auth');
Route::post('/categories', 'CategoriesController@store');
Route::get('/categories/create', 'CategoriesController@create')
    ->middleware('auth');
Route::get('/categories/{category}', 'CategoriesController@show')->name('categories.show')
    ->middleware('auth');
Route::get('/categories/{category}/edit', 'CategoriesController@edit')
    ->middleware('auth');
Route::put('/categories/{category}', 'CategoriesController@update');
Route::delete('categories/{category}', 'CategoriesController@destroy');

// Route for Market Days index and individual pages
Route::get('/market_days', 'MarketDaysController@index')
    ->middleware('auth');
// Route::post('/market_days', 'MarketDaysController@store');

// Display index of Completed Market Days
// Route::get('/market_days/completed', 'MarketDaysController@completedindex')->name('completed-index')
// ->middleware('auth');

//testing
Route::get('/market_days/completed', 'MarketDaysController@completedindex')->name('completed-index');
Route::get('/market_days/completed/getdata', 'MarketDaysController@getdata')->name('completed-index.getdata');


Route::get('/market_days/create-setup', 'MarketDaysController@createStep1')
    ->middleware('auth');
Route::post('/market_days/create-setup', 'MarketDaysController@postCreateStep1')
    ->middleware('auth');
Route::get('/market_days/create', 'MarketDaysController@createStep2')
    ->middleware('auth');
Route::post('/market_days/create', 'MarketDaysController@store');
Route::get('/market_days/{market_day}', 'MarketDaysController@show')->name('market_days.show')
    ->middleware('auth');
Route::get('/market_days/{market_day}/edit', 'MarketDaysController@edit')
    ->middleware('auth');
Route::put('/market_days/{market_day}', 'MarketDaysController@update');
Route::delete('/market_days/{market_day}', 'MarketDaysController@destroy');
