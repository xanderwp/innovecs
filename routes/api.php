<?php

use Illuminate\Http\Request;

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

Route::namespace('api')->group(function () {

    Route::post('/login','AuthController@login');
    Route::post('/reg','AuthController@reg');

    Route::middleware(['auth:api'])->group(function () {

        Route::get('/logout','AuthController@logout');
        Route::put('/add/{email}/{amount}','TransactionsController@add');
        Route::get('/get-report-month','TransactionsController@getReportMonth');
        Route::get('/get-report-week','TransactionsController@getReportWeek');

    });
});
