<?php

use App\Http\Controllers\{StateController, LocationController, CategoryController, ItemController, ReportController, MessageController, ConversationController, OverViewController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{RegisterController, LoginController};
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

Route::get('user', function (Request $request) {
    $user = auth()->user();
    return $user;
})->middleware('auth:sanctum');


Route::get('overview', OverViewController::class);


Route::resource('states', StateController::class);
Route::resource('locations', LocationController::class);

Route::resource('categories', CategoryController::class);
Route::resource('items', ItemController::class);

Route::resource('reports', ReportController::class);
Route::get('conversations/user', [ConversationController::class, 'user']);
Route::resource('conversations', ConversationController::class);


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
