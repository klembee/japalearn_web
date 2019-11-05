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

Route::prefix('auth')->group(function(){
    Route::post('login', 'Api\AuthApiController@login');
    Route::post('register', 'Api\AuthApiController@register');
    Route::middleware('auth:api')->post('verify', 'Api\AuthApiController@verify');
});

Route::prefix('kanji')->group(function(){
    Route::get('{kanji}', 'Api\KanjiApiController@viewKanji');

    Route::prefix('categories')->group(function(){
        Route::get('', 'Api\KanjiApiController@categories');
        Route::get('view', 'Api\KanjiApiController@kanjiForCategory');
    });
    Route::get('view/{kanji}', 'Api\KanjiApiController@view')->name('kanji.view');
    Route::get('jlpt/{level}', 'Api\KanjiApiController@jlptLevel');
});

Route::prefix('dictionary')->group(function(){
    Route::get('', "Api\DictionaryApiController@search");
});

Route::apiResource('feedback', 'Api\FeedbackController')->only('store');
