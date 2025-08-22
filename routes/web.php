<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PettyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplenishmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\WarrantyController;
use App\Livewire\WebsitePanel;
use Illuminate\Support\Facades\Route;

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

    Route::post('/petty/{id}/upload-attachment', [PettyController::class, 'updateAttachment'])->name('petty.updateAttachment');

    Route::resource('warranty', WarrantyController::class);
});


Route::middleware(['auth', 'permission:request pettycash'])->group(function () {
    Route::resource('petty', PettyController::class);

    Route::get('/pettycash/request/step1', [PettyController::class, 'step1'])->name('petty.create.step1');
    Route::get('/pettycash/step1/{id}', [PettyController::class, 'step1'])->name('petty.edit.step1');
    Route::post('/pettycash/request/step1', [PettyController::class, 'storeStep1'])->name('petty.store.step1');
    Route::put('/pettycash/step1/{id}', [PettyController::class, 'updateStep1'])->name('petty.update.step1');

    Route::get('/pettycash/request/step2', [PettyController::class, 'step2'])->name('petty.create.step2');
    Route::get('/pettycash/step2/{id}', [PettyController::class, 'step2'])->name('petty.edit.step2');
    Route::put('/pettycash/step2/{id}', [PettyController::class, 'updateStep2'])->name('petty.update.step2');
    Route::post('/pettycash/request/step2', [PettyController::class, 'storeStep2'])->name('petty.store.step2');

    Route::get('/pettycash/request/step3', [PettyController::class, 'step3'])->name('petty.create.step3');
    Route::get('/pettycash/step3/{id}', [PettyController::class, 'step3'])->name('petty.edit.step3');
    Route::post('/pettycash/request/step3', [PettyController::class, 'storeStep3'])->name('petty.store.step3');
    Route::put('/pettycash/step3/{id}', [PettyController::class, 'updateStep3'])->name('petty.update.step3');

    Route::get('/pettycash/request/step4', [PettyController::class, 'step4'])->name('petty.create.step4');
    Route::get('/pettycash/step4/{id}', [PettyController::class, 'step4'])->name('petty.edit.step4');
    Route::post('/pettycash/request/step4', [PettyController::class, 'storeStep4'])->name('petty.store.step4');
    Route::put('/pettycash/step4/{id}', [PettyController::class, 'updateStep4'])->name('petty.update.step4');

    Route::get('/autocomplete-stops', [PettyController::class, 'autocomplete'])->name('stops.autocomplete');
});

Route::middleware(['auth', 'permission:view requested pettycash'])->group(function () {
    Route::get('pettycash/requests/list', [PettyController::class, 'requests_list'])->name('petty.list');
    Route::get('/pettycash/request/{hashid}/details', [PettyController::class, 'request_show'])->name('petty.details');
    Route::post('f_approve/{id}', [PettyController::class, 'f_approve'])->name('f_approve.approve');
    Route::post('l_approve/{id}', [PettyController::class, 'l_approve'])->name('l_approve.approve');
    Route::get('c_approve/{id}', [PettyController::class, 'c_approve'])->name('c_approve.approve');
    Route::post('/petty/reject/{id}', [PettyController::class, 'reject'])->name('petty.reject');

    Route::resource('replenishment', ReplenishmentController::class);
    Route::post('/replenishment/initial/approve/{id}', [ReplenishmentController::class, 'firstApprove'])->name('initial.approve');
    Route::post('/replenishment/last/approve/{id}', [ReplenishmentController::class, 'lastApprove'])->name('last.approve');
    Route::get('/replenishment/petty/cash/list', [ReplenishmentController::class, 'pettycash'])->name('replenishment.pettycash');
    Route::get('/replenishment/{id}/download', [ReplenishmentController::class, 'downloadPDF'])->name('replenishment.download');
});

Route::middleware(['auth', 'permission:last pettycash approval'])->group(function () {
    Route::get('pettycash/all/requests/list', [PettyController::class, 'all_requests'])->name('all.pettycash');
});

Route::middleware(['auth', 'permission:view cashflow movements'])->group(function () {
    Route::resource('deposit', DepositController::class);
    Route::get('/pettycash/flow', [DepositController::class, 'cashflow'])->name('cashflow.index');
    Route::get('/cashflow/download', [DepositController::class, 'download'])->name('cashflow.download');
    Route::get('pettycash/requests/payments/list', [PettyController::class, 'requestsCashier'])->name('petty.cashier');
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
    Route::get('reports/petty/cash/transactions', [ReportController::class, 'transactionReport'])->name('reports.transaction');
    Route::get('/reports/petty/cash/transaction/download/{type}', [ReportController::class, 'downloadTransaction'])->name('reports.transaction.download');

    Route::get('reports/routes/prices', [ReportController::class, 'routeReport'])->name('reports.routes');
    Route::get('/reports/routes/download', [ReportController::class, 'downloadRouteReport'])->name('reports.route.download');
});

Route::middleware(['auth', 'permission:view settings'])->group(function () {
    Route::get('/settings/routes', [TransportController::class, 'routes'])->name('settings.routes');
    Route::get('/settings/destination/stops', [TransportController::class, 'destination'])->name('settings.destination');
    Route::post('/settings/picking/points', [TransportController::class, 'storeStart'])->name('start.store');
    Route::post('/settings/transport/mode', [TransportController::class, 'storeTransport'])->name('transport.store');
    Route::patch('/picking-point/{id}/toggle', [TransportController::class, 'toggleStatus'])->name('picking-point.toggle');
    Route::patch('/transport/mode/{id}/toggle', [TransportController::class, 'transStatus'])->name('trans.toggle');
});

Route::middleware(['auth', 'permission:catalogue management'])->group(function () {
    Route::get('/catalogues/list', [CatalogueController::class, 'index'])->name('catalogues.index');
    Route::get('/catalogues/{id}/show', [CatalogueController::class, 'show'])->name('catalogues.show');
    Route::post('/', [CatalogueController::class, 'store'])->name('catalogues.store');
    Route::post('/catalogue/{id}/upload-file', [CatalogueController::class, 'storeFile'])->name('catalogue.file.store');
    Route::get('/{id}/edit', [CatalogueController::class, 'edit'])->name('catalogues.edit');
    Route::put('/{id}', [CatalogueController::class, 'update'])->name('catalogues.update');
    Route::delete('/{id}', [CatalogueController::class, 'destroy'])->name('catalogues.destroy');
});

Route::middleware(['auth', 'permission:mars website management'])->group(function () {
    Route::get('/website-settings', function () {
        return view('website.index');
    })->name('website.settings');
});


require __DIR__ . '/auth.php';
