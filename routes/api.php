<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ExbitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\PharmacyTasks;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\VisitorController;

Route::post('auth/register',[AuthenticationController::class,'register']);
Route::post('auth/login',[AuthenticationController::class,'login']);
Route::post('auth/logout',[AuthenticationController::class,'logout']);


  Route::get('auth/getProfile', [AuthenticationController::class, 'getProfile'])
  ->middleware('auth:sanctum');


//Fetching data route to the app
  Route::get('/getAgenda', [AgendaController::class, 'getAgenda']);
  Route::get('/getLocation', [LocationController::class, 'getLocation']);
  Route::get('/getExbitor', [ExbitorController::class, 'getExbitor']);
  Route::get('/getVisitor', [VisitorController::class, 'getVisitor']);
  Route::get('/getSpeaker', [SpeakerController::class, 'getSpeaker']);


Route::post('/bookMeeting',[MeetingController::class,'bookMeeting']);
Route::get('/getBooking', [MeetingController::class, 'getBooking'])->middleware('auth:sanctum');
Route::get('/receivedMeeting', [MeetingController::class, 'receivedMeeting']);
Route::get('/meeting', [MeetingController::class, 'meeting']);
Route::get('/exmeeting', [MeetingController::class, 'exmeeting']);
Route::post('/update-status', [MeetingController::class, 'updateStatus']);


//   Route::delete('/orders/{id}', [PharmacyTasks::class, 'deleteCartItem'])->middleware('auth:sanctum');
//   Route::delete('/orderHistory/{id}', [PharmacyTasks::class, 'deleteCartHistoryItem'])->middleware('auth:sanctum');

//   Route::post('/moveCartsToOrderHistory',[PharmacyTasks::class,'moveCartsToOrderHistory'])
//   ->middleware('auth:sanctum');
//   Route::get('/getMyCartHistory',[PharmacyTasks::class,'getMyCartHistory'])
//     ->middleware('auth:sanctum');

//     Route::post('/sendOrderToPharmacy',[PharmacyTasks::class,'sendOrderToPharmacy'])
//     ->middleware('auth:sanctum');
