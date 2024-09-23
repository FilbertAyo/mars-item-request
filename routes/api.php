<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ExbitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;


Route::post('auth/register',[AuthenticationController::class,'register']);
Route::post('auth/login',[AuthenticationController::class,'login']);
Route::post('auth/logout',[AuthenticationController::class,'logout']);


  Route::get('auth/getProfile', [AuthenticationController::class, 'getProfile'])
  ->middleware('auth:sanctum');


