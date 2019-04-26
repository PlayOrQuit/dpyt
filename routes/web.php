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


use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group([
    'prefix' => 'admin',
    'middleware' => [
        'auth',
    ],
], function() {
    /**
     * begin api key
     */
    Route::group([ 'prefix' => 'api-key'], function() {
        Route::get('/', 'APIKeyController@render');
        Route::get('/get', 'APIKeyController@get')->middleware(['cors']);
        Route::post('/create', 'APIKeyController@create')->middleware(['cors']);
        Route::put('/edit', 'APIKeyController@edit')->middleware(['cors']);
    });

    Route::group([ 'prefix' => 'channel'], function() {
        Route::get('/', 'ViewChannelController@view_list');
    });


    /**
     * end api key
     */
});
