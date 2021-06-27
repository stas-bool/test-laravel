<?php

use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\UserNewsController;
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

// User group
Route::group(['middleware' => ['permission:read news']], function () {
    Route::get('/user/news/', [UserNewsController::class, 'index']);
    Route::get('/user/news/{id}', [UserNewsController::class, 'show']);
});

//Admin group
Route::group(['middleware' => ['role:admin']], function () {
    Route::patch('/admin/news/{id}', [AdminNewsController::class, 'update']);
    Route::get('/admin/news/', [AdminNewsController::class, 'index']);
    Route::get('/admin/news/{id}', [AdminNewsController::class, 'show']);
    Route::post('/admin/news/', [AdminNewsController::class, 'create']);
});

