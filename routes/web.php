<?php

use App\Http\Controllers\AccountExecutiveController;
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



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'showDashboardFields'])
    ->name('dashboard');

Route::view('advisories', 'advisories')
    ->middleware(['auth', 'verified'])
    ->name('advisories');

Route::view('my-contracts', 'my-contracts')
    ->middleware(['auth', 'verified'])
    ->name('my-contracts');

Route::get('/my-bills', [BillController::class, 'showBillsPage'])
    ->middleware(['auth', 'verified'])
    ->name('bills.show');

Route::get('/payment-history', [BillController::class, 'showPaymentHistory'])
    ->middleware(['auth', 'verified'])
    ->name('payments.history');


Route::middleware(['auth'])->group(function () {

    

    Route::resource('profiles', ProfileController::class);


    Route::resource('users', UserController::class);

    Route::put('/all-user-list/{user}', [UserController::class, 'editAllUsers'])->name('all-user-list.update');

    //admin accounts
    Route::get('/admin-list', [UserController::class, 'showAdmins'])
        ->name('admin-list');

        Route::post('/admin/users/store-admin', [UserController::class, 'storeAdmins'])
        ->name('admin.users.store-admin');

        Route::post('/admin/users/store-account-executive', [UserController::class, 'storeAE'])
        ->name('admin.users.store-account-executive');

    Route::get('/account-executive-list', [UserController::class, 'showAE'])
        ->name('account-executive-list');

    Route::get('/all-user-list', [UserController::class, 'showAllUsers'])
        ->name('all-user-list');

    Route::get('/energy-consumption', [GhgController::class, 'calculateEmissions'])
        ->name('energy-consumption');


    Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
    Route::post('/role-permission', [RolePermission::class, 'store'])->name('role.permission.store');
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::delete('roles/{id}', [RolePermission::class, 'destroyRole'])->name('roles.destroy');
    Route::delete('permissions/{id}', [RolePermission::class, 'destroyPermission'])->name('permission.destroy');

    // Route::resource('account-executives', AccountExecutiveController::class);

    Route::resource('advisories-management', AdvisoryController::class);
    Route::get('/admin/advisories', [AdvisoryController::class, 'adminList'])->name('advisories.list');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
