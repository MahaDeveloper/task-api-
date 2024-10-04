<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use OpenApi\Attributes as OA;
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


Route::group(['namespace' => 'App\Http\Controllers\Api'],function()
{

    Route::post('login', 'AuthController@login');

    Route::middleware(['jwt.auth'])->group(function () {

        Route::post('logout', 'AuthController@logout');

        Route::resource('tasks', 'TaskController');

        Route::post('assign-task', 'TaskController@assignTask');
    });


});