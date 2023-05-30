<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('posts', PostController::class);
    Route::post('/posts/{id}', [PostController::class, 'update']);
});
