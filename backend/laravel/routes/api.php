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
    Route::post('/send_email', 'ResetPasswordController@sendEmail')->name('send_email');
    Route::post('/reset_password', 'ResetPasswordController@resetPassword')->name('reset_password');
    Route::put('/post_image', 'ImageController@s3')->name('post_image');

    Route::prefix('party')->name('party.')->group(function () {
        Route::post('/register', 'PartyController@register')->name('register');
        Route::get('/get/{id}', 'PartyController@getData')->name('get');
        Route::post('/join', 'PartyController@join')->name('join');
        Route::get('/check_if_joined/{party_id}', 'PartyController@checkIfJoined')->name('check_if_joined');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/get/{id}', 'UserController@getData')->name('get');
        Route::get('/get_auth', 'UserController@getAuthData')->name('get_auth');
        Route::post('/update_auth/{auth_id}', 'UserController@updateAuthData')->name('update_auth');
    });

    Route::prefix('message')->name('message.')->group(function () {
        Route::get('/index', 'MessageController@index')->name('index');
        Route::get('/index_for_leader', 'MessageController@indexForLeader')->name('index_for_leader');
        Route::get('/get/{message_group_id}', 'MessageController@getMessage')->name('get');
        Route::post('/send_message', 'MessageController@sendMessage')->name('send_message');
    });
    Route::prefix('search')->name('search')->group(function () {
        Route::get('', 'SearchController@index');
    });
    Route::namespace('Master')->group(function () {
        Route::get('/get_prefs', 'PrefController@index')->name('prefs');
        Route::get('/get_parties', 'PartyController@index')->name('parties');
        Route::get('/get_created_parties', 'PartyController@indexCreated')->name('created_parties');
        Route::get('/get_participated_parties', 'PartyController@indexParticipated')->name('participated_parties');
        Route::get('/get_tags', 'TagController@index')->name('tags');
    });
});
