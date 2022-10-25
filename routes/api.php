<?php

use App\Http\Controllers\API\FolderController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TagController;
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

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiresources([
        'bookmark' => BookmarkController::class,
    ]);

    Route::apiresources([
        'folder' => FolderController::class,
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(TagController::class)->group(function () {
        Route::post('tag', 'storeforfolder');
        Route::post('tag', 'storeforbookmark');
    });
});
