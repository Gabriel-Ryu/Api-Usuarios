<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('api')->get('/authentication', function(Request $request){
//     return $request->user();
// });

// Route::group(['prefix' => 'users', 'middleware' => 'auth:api'],function(){
//     Route::post('delete/{id}', [UserController::class, 'delete']);
//     Route::post('create', [UserController::class, 'create']);
//     Route::get('me', [UserController::class, 'checkUser']);
//     Route::put('update/{id}', [UserController::class, 'update']);
//     Route::put('retore/{id}', [UserController::class, 'retore']);
// });

Route::get('checkToken',[TokenController::class, 'checkToken'])->name('checkToken');
// Route::post('checkToken',[TokenController::class, 'checkToken'])->name('checkToken');

Route::prefix('users')->middleware('auth:api')->group(function () {
    Route::post('delete/{id}', [UserController::class, 'delete']);
    Route::post('create', [UserController::class, 'create']);
    Route::get('me', [UserController::class, 'checkUser']);
    Route::put('update/{id}', [UserController::class, 'update']);
    Route::put('retore/{id}', [UserController::class, 'retore']);
});




Route::prefix('auth')->group(function () {
    Route::post('register', [AuthenticatedController::class, 'register']);
    Route::post('login', [AuthenticatedController::class, 'login']);
});
