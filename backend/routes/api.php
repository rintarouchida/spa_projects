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
    Route::put('/register', 'AuthController@register')->name('register');
    Route::get('/get_auth', 'AuthController@getAuthData')->name('get_auth');
    Route::put('/update_auth/{auth_id}', 'AuthController@updateAuthData')->name('update_auth');
    Route::post('/send_email', 'ResetPasswordController@sendEmail')->name('send_email');
    Route::post('/reset_password', 'ResetPasswordController@resetPassword')->name('reset_password');

    Route::prefix('party')->name('party.')->group(function () {
        Route::get('/index', 'PartyController@index')->name('index');
        Route::get('/index_created', 'PartyController@indexCreated')->name('index_created');
        Route::get('/index_participated', 'PartyController@indexParticipated')->name('index_participated');
        Route::get('/get/{id}', 'PartyController@getData')->name('get');
        Route::get('/check_if_joined/{party_id}', 'PartyController@checkIfJoined')->name('check_if_joined');
        Route::get('/check_if_editable/{party_id}', 'PartyController@checkIfEditable')->name('check_if_editable');
        Route::put('/register', 'PartyController@register')->name('register');
        Route::post('/join', 'PartyController@join')->name('join');
        Route::delete('/cancel/{id}', 'PartyController@cancel')->name('cancel');
        Route::get('/search', 'PartyController@search')->name('search');
        Route::get('/edit/{id}', 'PartyController@edit')->name('edit');
        Route::put('/update/{id}', 'PartyController@update')->name('update');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/get/{id}', 'UserController@getData')->name('get');
    });

    Route::prefix('message')->name('message.')->group(function () {
        Route::get('/index', 'MessageController@index')->name('index');
        Route::get('/index_for_leader', 'MessageController@indexForLeader')->name('index_for_leader');
        Route::get('/get/{message_group_id}', 'MessageController@getMessage')->name('get');
        Route::post('/send_message', 'MessageController@sendMessage')->name('send_message');
    });
});
