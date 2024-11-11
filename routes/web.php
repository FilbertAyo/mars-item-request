<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
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

Route::get('/', [ProfileController::class, 'redirect']) ->middleware(['auth', 'verified']);

Route::get('/dashboard', [ProfileController::class, 'redirect'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';


// admin
Route::resource('admin', AdminController::class);
Route::get('/branch_list', [AdminController::class, 'branch_list']);
Route::post('/add_branch', [AdminController::class, 'branch_store']);
Route::delete('/destroy_branch/{id}', [AdminController::class, 'destroy_branch']);

// department
Route::resource('department', DepartmentController::class);
Route::post('/justify', [DepartmentController::class, 'justify']);

// branch manager
Route::resource('branch', BranchController::class);
Route::get('/item/approve/{id}', [BranchController::class, 'approve'])->name('item.approve');
Route::post('/item/reject/{id}', [BranchController::class, 'reject'])->name('item.reject');


//general manager
Route::resource('general', GeneralController::class);
Route::get('/purchase/approve/{id}', [GeneralController::class, 'approve'])->name('purchase.approve');
Route::post('/purchase/reject/{id}', [GeneralController::class, 'reject'])->name('purchase.reject');
Route::get('record', [GeneralController::class, 'record']);
Route::get('/items/filter', [GeneralController::class, 'record'])->name('items.filter');

//petty cash
Route::resource('petty', PettyController::class);
Route::get('petty_first_approval', [PettyController::class, 'first_approval']);
Route::get('/first_approval/{id}', [PettyController::class, 'first_show'])->name('first_approval.show');
Route::get('f_approve/{id}', [PettyController::class, 'f_approve'])->name('f_approve.approve');
Route::get('l_approve/{id}', [PettyController::class, 'l_approve'])->name('l_approve.approve');
Route::get('c_approve/{id}', [PettyController::class, 'c_approve'])->name('c_approve.approve');
Route::post('/petty/reject/{id}', [PettyController::class, 'reject'])->name('petty.reject');
Route::resource('deposit', DepositController::class);
