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
// use App\Livewire\RoleForm;
use App\Livewire\RolePermissionMatrix;


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

    Route::resource('users', UserController::class);

    Route::get('/energy-consumption', [GhgController::class, 'calculateEmissions'])
    ->name('energy-consumption');

    Route::resource('profiles', ProfileController::class);


    // Route::get('/roles-permissions', RolePermissionMatrix::class)->name('role-permissions');

    Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
    Route::post('/role-permission', [RolePermission::class, 'store'])->name('role.permission.store');
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);

    Route::resource('advisories-management', AdvisoryController::class);

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
