<?php

use App\Http\Controllers\FileAddAndDeleteController;
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
});
