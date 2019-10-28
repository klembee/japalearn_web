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

use Illuminate\Support\Facades\Route;

Route::get('/', 'DictionaryController@index')->name('index');
Route::get('/view/{id}', 'DictionaryController@view')->name('view');
Route::get('/search', 'DictionaryController@search')->name('search');
Route::get('/login')->name('login');
