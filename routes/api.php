<?php

use App\Http\Controllers\NewsController;
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

Route::group(['middleware' => ['role:user|admin']], function () {
    Route::resource('news', NewsController::class)
        ->only(['index', 'show']);
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('news', NewsController::class)
        ->only(['store', 'update']);
});
