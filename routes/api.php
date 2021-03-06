<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/user', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');    

// Route::group(['middleware' => ['auth:sanctum']], function () {    
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });    
// });
Route::post('post/{post}', [PostController::class, 'update']);
Route::resource('post',PostController::class)->only(['index','store','show','destroy']);

Route::resource('users',UserController::class)->only(['index','store','show','update','destroy']);