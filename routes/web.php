<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('home', function () {
    return view('home');
})->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/index', [AdminController::class, 'index'])->middleware('auth')->name('index');

Route::get('admin/manageuser', [AdminController::class, 'listuser'])->middleware('auth')->name('manage');

Route::get('admin/createuser/', [AdminController::class, 'createuser'])->middleware('auth')->name('createuser');

Route::post('admin/createuser', [AdminController::class, 'postcreateuser'])->middleware('auth')->name('postuser');
