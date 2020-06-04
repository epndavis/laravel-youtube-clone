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

Route::prefix('/videos')->group(function() {
    Route::get('/', 'Api\VideoController@index')->name('video.index');
    Route::get('/{id}', 'Api\VideoController@show')->name('video.show');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
