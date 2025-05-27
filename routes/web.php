<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ExbitorController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PettyController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'redirect'])->middleware(['auth', 'verified']);

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //branches
    Route::get('/branches/list', [BranchController::class, 'index'])->name('branches');
    Route::post('/add/branch', [BranchController::class, 'store'])->name('branches.store');
    Route::delete('/destroy/branch/{id}', [BranchController::class, 'destroy'])->name('branch.destroy');
    Route::get('/branch/{id}', [BranchController::class, 'show'])->name('branch.show');

    Route::resource('department', DepartmentController::class);

    // admin
    Route::resource('admin', AdminController::class);
    Route::post('/activate/{id}', [AdminController::class, 'activate'])->name('admin.activate');

    //petty cash
    Route::resource('petty', PettyController::class);
    Route::get('pettycash/requests/list', [PettyController::class, 'requests_list'])->name('petty.list');
    Route::get('/pettycash/request/{id}/details', [PettyController::class, 'request_show'])->name('petty.details');
    Route::get('f_approve/{id}', [PettyController::class, 'f_approve'])->name('f_approve.approve');
    Route::get('l_approve/{id}', [PettyController::class, 'l_approve'])->name('l_approve.approve');
    Route::get('c_approve/{id}', [PettyController::class, 'c_approve'])->name('c_approve.approve');
    Route::post('/petty/reject/{id}', [PettyController::class, 'reject'])->name('petty.reject');
    Route::resource('deposit', DepositController::class);


    // branch manager
    // Route::get('/item/approve/{id}', [BranchController::class, 'approve'])->name('item.approve');
    // Route::post('/item/reject/{id}', [BranchController::class, 'reject'])->name('item.reject');


    //general manager
    Route::resource('general', GeneralController::class);
    Route::get('/purchase/approve/{id}', [GeneralController::class, 'approve'])->name('purchase.approve');
    Route::post('/purchase/reject/{id}', [GeneralController::class, 'reject'])->name('purchase.reject');
    Route::get('record', [GeneralController::class, 'record']);
    Route::get('/items/filter', [GeneralController::class, 'record'])->name('items.filter');

    // department

    Route::post('/justify', [ItemRequestController::class, 'justify']);
    Route::resource('item-request', ItemRequestController::class);
});


require __DIR__ . '/auth.php';
