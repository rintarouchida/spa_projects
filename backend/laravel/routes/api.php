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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// SPA認証
Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user_info', 'AuthController@get');
    Route::post('/register', 'AuthController@register')->name('register');
    Route::post('/edit', 'AuthController@edit');
    Route::prefix('reset_password')->name('reset_password.')->group(function () {
        Route::post('/send_email', 'ResetPasswordController@SendEmail')->name('send_email');
    });
    Route::prefix('party')->name('party.')->group(function () {
        Route::post('/register', 'PartyController@register')->name('register');
        Route::get('/get/{id}', 'PartyController@getData')->name('get');
        Route::post('/join', 'PartyController@join')->name('join');
        Route::get('/check_if_joined/{party_id}', 'PartyController@checkIfJoined')->name('check_if_joined');
    });
    Route::prefix('message')->name('message.')->group(function () {
        Route::get('/index', 'MessageController@index')->name('index');
        Route::get('/index_for_leader', 'MessageController@indexForLeader')->name('index_for_leader');
        Route::get('/get/{message_group_id}', 'MessageController@getMessage')->name('get');
        Route::get('/get_party_theme/{message_group_id}', 'MessageController@getPartyThemeByMessageGroup')->name('get_party_theme');
        Route::post('/send_message', 'MessageController@sendMessage')->name('send_message');
    });
    Route::prefix('search')->name('search')->group(function () {
        Route::get('', 'SearchController@index');
    });
    Route::namespace('Master')->group(function () {
        Route::get('/get_prefs', 'PrefController@index')->name('prefs');
        Route::get('/get_parties', 'PartyController@index')->name('parties');
        Route::get('/get_tags', 'TagController@index')->name('tags');
    });
});

