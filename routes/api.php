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
	Route::controller(FolderController::class)->group(function () {
		Route::delete('folder/{folder}/tag/{tag}', 'destroyforfolder');
		Route::get('{folder}/content/bookmark', 'bookmarkinfolder');
		Route::get('{folder}/content/folder', 'folderinfolder');
		Route::get('{folder}/content', 'content');
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


/*Route::get('bookmark/id/tags');
Route::get('folder/id/tags');*/
/*
/// DONE 
- Commenter toutes les fonctions
	- Models :
		- bookmark
		- folder
		- tag
		- user
- Can't destroy root folder and can't tag him
- Fix tag store fonctionnality
- Error when no id is paced in the body
- Codes d'erreurs à vérifier
- Renvoie en JSON
- validator for :
	- bookmark store
	- bookmark update
	- folder store
	- folder update
	- tag store
	- tag update
- can't create two tags with same name on same taggables
- for root problem -> idparent  = null
///

- CRUD for tags
	- Index /// DONE
	- Update /// DONE
	- Destroy
- function "Search"
- function "Content" for folder
- générer doc postman
- doc github a écrire
- fix can't attach 2 times a same tags on the same taggable
- check where the function are and her relation (cohérence)


/// exist in change , need to attach tag_id and taggable_id*/
