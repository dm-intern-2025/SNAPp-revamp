<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\RolePermission;
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

    // Route::view('my-bills', 'my-bills')
    // ->middleware(['auth', 'verified'])
    // ->name('my-bills');

    Route::get('/bills', [BillController::class, 'showBillsPage'])
    ->middleware(['auth', 'verified'])
    ->name('my-bills');

    Route::view('my-energy-consumption', 'my-energy-consumption')
    ->middleware(['auth', 'verified'])
    ->name('my-energy-consumption');

    Route::view('my-ghg-emissions', 'my-ghg-emissions')
    ->middleware(['auth', 'verified'])
    ->name('my-ghg-emissions');

    // Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    //     return response()->json($request->user());
    // });


Route::middleware(['auth'])->group(function () {

    Route::get('/roles-permissions', RolePermissionMatrix::class)->name('role-permissions');
    
    // Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
    // Route::post('/role-permission', [RolePermission::class, 'store'])->name('role.permission.store');
    // Route::resource('permission', PermissionController::class);
    // Route::resource('role', RoleController::class);

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
