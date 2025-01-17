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
, "middleware" => "bitacoraAuth"
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function()
{
	Route::post('/verify/debit',[ 
        'uses' => 'DebitController@verifyDebit', 
        'as' => 'verify.debit'
    ]);

    Route::group(["prefix" => "bitacora"], function() {

    	Route::get('/watch', [
    		'uses' => 'LogController@watchState',
    		'as' => 'watch'
    	]);

    });
});