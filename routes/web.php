<?php

use App\Http\Controllers\AdvisoryController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GhgController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\RolePermission;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('account/deactivated', function () {
    return view('auth.deactivated');
})->name('account.deactivated');

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
//ALL ACCESS
Route::middleware(['role:admin|account executive|customer'])->group(function () {
    Route::resource('profiles', ProfileController::class);
    Route::get('/dashboard/load-more', [DashboardController::class, 'loadMore'])->name('dashboard.load-more');
    Route::view('my-contracts', 'my-contracts')->name('my-contracts');
    Route::get('/dashboard', [DashboardController::class, 'showDashboardFields'])->name('dashboard');
    Route::get('/advisories', [AdvisoryController::class, 'index'])->name('advisories.index');
    Route::get('/advisories/load-more', [AdvisoryController::class, 'loadMore'])->name('advisories.load-more');

});
Route::middleware(['role:admin|customer'])->group(function () {
    Route::get('/my-bills', [BillController::class, 'showBillsPage'])->name('bills.show');
    Route::get('/payment-history', [BillController::class, 'showPaymentHistory'])->name('payments.history');
    Route::get('/bills/export', [BillController::class, 'exportBills'])->name('bills.export');
    Route::get('/payments/export', [BillController::class, 'exportPayments'])->name('payments.export');
    Route::get('/energy-consumption', [GhgController::class, 'calculateEmissions'])->name('energy-consumption');
});

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Admin-only routes inside role:admin middleware
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::put('/all-user-list/{user}', [UserController::class, 'editAllUsers'])->name('all-user-list.update');
        Route::get('/admin-list', [UserController::class, 'showAdmins'])->name('admin-list');
        Route::post('/admin/users/store-admin', [UserController::class, 'storeAdmins'])->name('admin.users.store-admin');
        Route::put('/admin/users/{user}/update-admin', [UserController::class, 'updateAdmins'])->name('admin.users.update-admin');
        Route::post('/admin/users/store-account-executive', [UserController::class, 'storeAE'])->name('admin.users.store-account-executive');
        Route::get('/account-executive-list', [UserController::class, 'showAE'])->name('account-executive-list');
        Route::get('/all-user-list', [UserController::class, 'showAllUsers'])->name('all-user-list');
        Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
        Route::post('/role-permission', [RolePermission::class, 'store'])->name('role.permission.store');
        Route::resource('permission', PermissionController::class);
        Route::resource('role', RoleController::class);
        Route::delete('roles/{id}', [RolePermission::class, 'destroyRole'])->name('roles.destroy');
        Route::delete('permissions/{id}', [RolePermission::class, 'destroyPermission'])->name('permission.destroy');

        Route::get('/admin/advisories', [AdvisoryController::class, 'adminList'])->name('advisories.list');
        Route::post('/advisories', [AdvisoryController::class, 'store'])->name('advisories.store');
        Route::put('/advisories/{advisory}', [AdvisoryController::class, 'update'])->name('advisories.update');
    });
});

require __DIR__ . '/auth.php';
