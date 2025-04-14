<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\UserController;

use App\Livewire\RolePermissionMatrix;


use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



Route::get('/', function () {
    return view('welcome');
})->name('home');

    Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::view('advisories', 'advisories')
    ->middleware(['auth', 'verified'])
    ->name('advisories');

    Route::view('my-contracts', 'my-contracts')
    ->middleware(['auth', 'verified'])
    ->name('my-contracts');

    Route::get('/bills', [BillController::class, 'showBillsPage'])
    ->middleware(['auth', 'verified'])
    ->name('my-bills');

    Route::view('my-energy-consumption', 'my-energy-consumption')
    ->middleware(['auth', 'verified'])
    ->name('my-energy-consumption');

    Route::view('my-ghg-emissions', 'my-ghg-emissions')
    ->middleware(['auth', 'verified'])
    ->name('my-ghg-emissions');


Route::middleware(['auth'])->group(function () {

    Route::get('/roles-permissions', RolePermissionMatrix::class)->name('role-permissions');

    Route::resource('users', UserController::class);


    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
