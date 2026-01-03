<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceApiController;

Route::get('/services', [ServiceApiController::class, 'index']);
Route::post('/services', [ServiceApiController::class, 'store']);
Route::get('/services/{id}', [ServiceApiController::class, 'show']);
Route::put('/services/{id}', [ServiceApiController::class, 'update']);
Route::delete('/services/{id}', [ServiceApiController::class, 'destroy']);


