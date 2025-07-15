<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function(){
    Route::post('search', [UserController::class,'search']);
    Route::apiResource('/', UserController::class)
         ->parameters(['' => 'user'])
         ->only(['index','store','show','update','destroy']);
});

Route::apiResource('appointments', AppointmentController::class)
     ->only(['index', 'store', 'show', 'update', 'destroy']);
