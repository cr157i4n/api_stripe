<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DeviceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Comercios
    Route::prefix('branches')->name('branches.')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::get('/edit', [BranchController::class, 'index'])->name('edit');
        Route::post('/store', [BranchController::class, 'store'])->name('store');
    });
});

Route::middleware(['auth','role:client'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    
    Route::prefix('devices')->name('client.devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::get('/create-code', [DeviceController::class, 'showCreateCodeForm'])->name('create-code');
        Route::post('/generate-code', [DeviceController::class, 'generateCode'])->name('generate-code');
        Route::get('/code-generated/{linkingCode}', [DeviceController::class, 'showGeneratedCode'])->name('code-generated');
        Route::post('/revoke-code/{linkingCode}', [DeviceController::class, 'revokeCode'])->name('revoke-code');
        Route::post('/toggle-status/{device}', [DeviceController::class, 'toggleStatus'])->name('toggle-status');
    });
    Route::prefix('transactions')->name('client.transactions.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
    });
    Route::prefix('activity')->name('client.activity.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
    });
});