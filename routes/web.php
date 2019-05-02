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
        Route::get('/getKeyByPrimary', 'APIKeyController@getKeyByPrimary')->middleware(['cors']);
        Route::post('/create', 'APIKeyController@create')->middleware(['cors']);
        Route::put('/editPrimary', 'APIKeyController@editPrimary')->middleware(['cors']);
        Route::delete('/delete', 'APIKeyController@delete')->middleware(['cors']);
    });
    /**
     * Channel
     */
    Route::group([ 'prefix' => 'channel'], function() {
        Route::get('/', 'ChannelController@render');
        Route::get('/callback', 'ChannelController@renderCallback');
        Route::get('/get', 'ChannelController@get')->middleware(['cors']);;
        Route::post('/create', 'ChannelController@create')->middleware(['cors']);;
        Route::delete('/delete', 'ChannelController@delete')->middleware(['cors']);;
    });
    /**
     * Region
     */
    Route::get('/regions', 'RegionController@get')->middleware(['cors']);;
    /**
     * Language
     */
    Route::get('/languages', 'LanguageController@get')->middleware(['cors']);;

    Route::group([ 'prefix' => 'playlist'], function() {
        Route::get('/', 'ViewMultiplePlayListController@view_index');


        Route::group([ 'prefix' => 'single'], function() {
            Route::get('/', 'SinglePlaylistController@render');
        });
    });



    /**
     * end api key
     */
});
