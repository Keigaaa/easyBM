<?php

use App\Http\Controllers\API\FolderController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\SearchController;
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
	Route::controller(FolderController::class)->group(function () {
		Route::delete('folder/{folder}/tag/{tag}', 'destroyforfolder');
		Route::get('{folder}/content/bookmark', 'bookmarkinfolder');
		Route::get('{folder}/content/folder', 'folderinfolder');
		Route::get('{folder}/content', 'content');
	});
});

Route::middleware('auth:sanctum')->group(function () {
	Route::controller(SearchController::class)->group(function () {
		Route::get('search/{search}', 'searchAll');
	});
});

Route::controller(BookmarkController::class)->group(function () {
	Route::delete('bookmark/{bookmark}/tag/{tag}', 'destroyforbookmark');
});

Route::middleware('auth:sanctum')->group(function () {
	Route::apiresources([
		'bookmark' => BookmarkController::class,
	]);

	Route::apiresources([
		'folder' => FolderController::class,
	]);
	Route::apiResource('tag', TagController::class,  ['only' => ['index', 'show', 'store', 'update']]);
});



/* Route::get('bookmark/id/tags');
Route::get('folder/id/tags');
- function "Search" (ccreate a searchcontroller)
- générer doc postman
- doc github a écrire
- check where the function are and her relation (cohérence)
*/
