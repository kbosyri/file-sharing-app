<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\FileAddAndDeleteController;
use App\Http\Controllers\GroupAddAndDeleteController;
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

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function (){
    Route::get('logout',[AuthController::class,'logout']);
    Route::post('/add-file',[FileAddAndDeleteController::class,'AddNewFileToGroup']);
    Route::delete('/delete-file',[FileAddAndDeleteController::class,'deleteFile']);
    Route::post('/add-file-to-group/{group_id}',[FileAddAndDeleteController::class,'AddFileToGroup']);
    Route::delete('/delete-file-from-group/{group_id}',[FileAddAndDeleteController::class,'deleteFileFromGroup']);
    Route::post('/add-group',[GroupAddAndDeleteController::class,'creategroup']);
    Route::delete('/delete-group/{group}',[GroupAddAndDeleteController::class,'deletegroup']);
    Route::post('/groups/{group}/add-user',[GroupAddAndDeleteController::class,'addusers']);
    Route::delete('/groups/{group}/delete-user',[GroupAddAndDeleteController::class,'deleteuser']);
    Route::get('/user/files',[DisplayController::class,'userfiles']);
    Route::get('/groups/{group}/files',[DisplayController::class,'groupfiles']);
});
