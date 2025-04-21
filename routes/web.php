<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GhgController;
use App\Http\Controllers\UserController;

use App\Livewire\RolePermissionMatrix;


use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



Route::get('/', function () {
    return view('welcome');
})->name('home');

    // Route::view('dashboard', 'dashboard')
    // ->middleware(['auth', 'verified'])
    // ->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'showDashboardFields'])
    ->name('dashboard');

    Route::get('/my-ghg-emissions', [GhgController::class, 'calculateEmissions'])
    ->name('my-ghg-emissions');

    

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

    Route::view('my-energy-consumption', 'my-energy-consumption')
    ->middleware(['auth', 'verified'])
    ->name('my-energy-consumption');

    // Route::view('my-ghg-emissions', 'my-ghg-emissions')
    // ->middleware(['auth', 'verified'])
    // ->name('my-ghg-emissions');


Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);

    Route::get('/roles-permissions', RolePermissionMatrix::class)->name('role-permissions');


    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
