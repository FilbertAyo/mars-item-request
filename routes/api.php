<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;

Route::get('/catalogues', [CatalogueController::class, 'apiIndex']);
Route::get('/catalogues/{catalogue}/latest', [CatalogueController::class, 'apiLatest']);
Route::get('/catalogues/{catalogue}/open', [CatalogueController::class, 'apiOpen']);


