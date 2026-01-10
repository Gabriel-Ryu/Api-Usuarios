<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('checkToken',[TokenController::class, 'checkToken'])->name('checkToken');

Route::prefix('users')->middleware('auth:api')->group(function () {
    Route::get('me', [UserController::class, 'checkUser']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthenticatedController::class, 'register']);
    Route::post('login', [AuthenticatedController::class, 'login']);
});
