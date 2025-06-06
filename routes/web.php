<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PettyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [DashboardController::class, 'redirect'])->middleware(['auth', 'verified']);

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/picture/update', [ProfileController::class, 'updateProfile'])->name('profile.image');

    //branches
    Route::get('/branches/list', [BranchController::class, 'index'])->name('branches');
    Route::post('/add/branch', [BranchController::class, 'store'])->name('branches.store');
    Route::delete('/destroy/branch/{hashid}', [BranchController::class, 'destroy'])->name('branch.destroy');
    Route::get('/branch/{hashid}', [BranchController::class, 'show'])->name('branch.show');
    Route::get('/departments/by/branch/{branchId}', [DepartmentController::class, 'getDepartmentsByBranch'])->name('departments.byBranch');

    Route::resource('department', DepartmentController::class);

    // admin
    Route::resource('admin', AdminController::class);
    Route::post('/activate/{id}', [AdminController::class, 'activate'])->name('admin.activate');
    Route::post('/users/{id}/assign-permissions', [AdminController::class, 'assignPermissions'])->name('assign.permissions');

    Route::resource('warranty',WarrantyController::class);

});


Route::middleware(['auth', 'permission:request pettycash'])->group(function () {
    Route::resource('petty', PettyController::class);
    Route::get('/autocomplete-stops', [PettyController::class, 'autocomplete'])->name('stops.autocomplete');
});

Route::middleware(['auth', 'permission:view requested pettycash'])->group(function () {
    Route::get('pettycash/requests/list', [PettyController::class, 'requests_list'])->name('petty.list');
    Route::get('/pettycash/request/{hashid}/details', [PettyController::class, 'request_show'])->name('petty.details');
    Route::post('f_approve/{id}', [PettyController::class, 'f_approve'])->name('f_approve.approve');
    Route::post('l_approve/{id}', [PettyController::class, 'l_approve'])->name('l_approve.approve');
    Route::get('c_approve/{id}', [PettyController::class, 'c_approve'])->name('c_approve.approve');
    Route::post('/petty/reject/{id}', [PettyController::class, 'reject'])->name('petty.reject');
});

Route::middleware(['auth', 'permission:view cashflow movements'])->group(function () {
    Route::resource('deposit', DepositController::class);
     Route::get('/pettycash/flow', [DepositController::class, 'cashflow'])->name('cashflow.index');
     Route::get('/cashflow/download', [DepositController::class, 'download'])->name('cashflow.download');

});

Route::middleware(['auth', 'permission:request item purchase'])->group(function () {
    Route::post('/item/request/justify', [ItemRequestController::class, 'justifyStore'])->name('justify.store');
    Route::get('/justify/{justify}/toggle-status', [ItemRequestController::class, 'toggleStatus'])->name('justify.toggleStatus');
    Route::get('/item/request/justification/list', [ItemRequestController::class, 'justify'])->name('justify.index');
    Route::resource('item-request', ItemRequestController::class);
});

Route::middleware(['auth', 'permission:approve item purchase'])->group(function () {
    Route::get('/item/request/approval', [ItemRequestController::class, 'request_list'])->name('item-request.list');
    Route::get('/item/request/{id}/approval', [ItemRequestController::class, 'details'])->name('item-request.details');
    Route::get('/purchase/approve/{id}', [ItemRequestController::class, 'approve'])->name('purchase.approve');
    Route::post('/purchase/reject/{id}', [ItemRequestController::class, 'reject'])->name('purchase.reject');
});

Route::middleware(['auth', 'permission:view reports'])->group(function () {
    Route::get('reports/list', [ReportController::class, 'index'])->name('reports');
    Route::get('reports/users', [ReportController::class, 'usersReport'])->name('reports.users');
    Route::get('/reports/users/download/{type}', [ReportController::class, 'downloadUsers'])->name('reports.users.download');
    Route::get('reports/petty/cash', [ReportController::class, 'pettyReport'])->name('reports.petties');
    Route::get('/reports/petty/cash/download/{type}', [ReportController::class, 'downloadPetty'])->name('reports.petties.download');
});


Route::middleware(['auth', 'permission:view settings'])->group(function () {
    Route::get('/settings/routes', [TransportController::class, 'routes'])->name('settings.routes');
    Route::get('/settings/destination/stops', [TransportController::class, 'destination'])->name('settings.destination');
    Route::post('/settings/picking/points', [TransportController::class, 'storeStart'])->name('start.store');
     Route::post('/settings/transport/mode', [TransportController::class, 'storeTransport'])->name('transport.store');
    Route::patch('/picking-point/{id}/toggle', [TransportController::class, 'toggleStatus'])->name('picking-point.toggle');
    Route::patch('/transport/mode/{id}/toggle', [TransportController::class, 'transStatus'])->name('trans.toggle');
});


Route::get('/run-optimize', function () {
    if (request()->get('key') !== 'secret2580') {
        abort(403);
    }
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'Optimization complete.';
});


// Route::get('/run-storage-link', function () {
//     if (request()->get('key') !== 'secret2580') {
//         abort(403);
//     }

//     Artisan::call('storage:link');

//     return 'Storage link created.';
// });

require __DIR__ . '/auth.php';
