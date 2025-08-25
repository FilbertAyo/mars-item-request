<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\WebsiteController;

Route::get('/catalogues', [CatalogueController::class, 'apiIndex']);
Route::get('/catalogues/{catalogue}/latest', [CatalogueController::class, 'apiLatest']);
Route::get('/catalogues/{catalogue}/open', [CatalogueController::class, 'apiOpen']);

Route::get('/website/home', [WebsiteController::class, 'home']);
Route::get('/website/partners', [WebsiteController::class, 'partners']);
Route::get('/website/services', [WebsiteController::class, 'services']);
Route::get('/website/faqs', [WebsiteController::class, 'faq']);

Route::get('/website/branches',[WebsiteController::class,'branches']);


