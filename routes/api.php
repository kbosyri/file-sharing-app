<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\FileAddAndDeleteController;
use App\Http\Controllers\FileOperationsController;
use App\Http\Controllers\GroupAddAndDeleteController;
use App\Http\Controllers\HistoryController;
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

Route::middleware(['acid','logger'])->post('register',[AuthController::class,'register']);
Route::middleware(['acid','logger'])->post('login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function (){

    Route::middleware(['acid','logger'])->group(function(){

        Route::get('logout',[AuthController::class,'logout']);

        /* File Add And Delete Controller Routes */
        Route::delete('/delete-file',[FileAddAndDeleteController::class,'deleteFile']);
        Route::delete('/delete-file-from-group/{group_id}',[FileAddAndDeleteController::class,'deleteFileFromGroup']);
        Route::post('/add-file',[FileAddAndDeleteController::class,'AddNewFileToGroup']);
        Route::post('/add-file-to-group/{group_id}',[FileAddAndDeleteController::class,'AddFileToGroup']);

        /* Group Add And Delete Controller Routes */
        Route::post('/add-group',[GroupAddAndDeleteController::class,'creategroup']);
        Route::delete('/delete-group/{group}',[GroupAddAndDeleteController::class,'deletegroup']);
        Route::post('/groups/{group}/add-user',[GroupAddAndDeleteController::class,'addusers']);
        Route::delete('/groups/{group}/delete-user',[GroupAddAndDeleteController::class,'deleteuser']);

        /* Display Controller Routes */ 
        Route::get('/user/files',[DisplayController::class,'userfiles']);
        Route::get('/user/files/reserved',[DisplayController::class,'userfilesreserved']);
        Route::get('/user/groups/',[DisplayController::class,'usergroups']);
        Route::get('/groups/{group}/files',[DisplayController::class,'groupfiles']);

        /*File Operations Controller Routes*/ 
        Route::get('/reqd-file/{uuid}',[FileOperationsController::class,'readFile']);
        Route::post('/files/check-in',[FileOperationsController::class,'bulk_check_in']);
        Route::post('/files/{uuid}/check-in',[FileOperationsController::class,'Check_in']);
        Route::get('/files/{uuid}/download',[FileOperationsController::class,'download_file']);
        Route::post('/files/{uuid}/check-out',[FileOperationsController::class,'check_out']);

        /* History Controller Routes */
        Route::get('/files/{id}/history',[HistoryController::class,'view_history']);

    });

    Route::middleware(['authorize.admin','acid','logger'])->group(function(){

        /* Admin Controller Routes */
        Route::get('/admin/all-files',[AdminController::class,'view_all_files']);
        Route::get('/admin/all-groups',[AdminController::class,'view_all_groups']);

    });
});
