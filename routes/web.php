<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\CashierController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DeviceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
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

// Client Routes
Route::middleware(['auth','role:client'])->group(function () {
    // Dashboard del Cliente
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    
    // Gestión de Cajas 
    Route::prefix('cashiers')->name('client.cashiers.')->group(function () {
        // Listar cajas
        Route::get('/', [CashierController::class, 'index'])->name('index');
        
        // Crear nueva caja
        Route::get('/create', [CashierController::class, 'create'])->name('create');
        Route::post('/', [CashierController::class, 'store'])->name('store');
        
        // Ver detalles de caja específica
        Route::get('/{cashier}', [CashierController::class, 'show'])->name('show');
        
        // Actualizar caja
        Route::put('/{cashier}', [CashierController::class, 'update'])->name('update');
        
        // Eliminar caja
        Route::delete('/{cashier}', [CashierController::class, 'destroy'])->name('destroy');
        
        // Generar código QR para una caja
        Route::post('/{cashier}/generate-code', [CashierController::class, 'generateCode'])->name('generate-code');
        
        // Mostrar código generado
        Route::get('/code/{qrDevice}', [CashierController::class, 'showGeneratedCode'])->name('code-generated');
        
        // Revocar código QR
        Route::delete('/code/{qrDevice}', [CashierController::class, 'revokeCode'])->name('revoke-code');
    });

    // API Routes para AJAX 
    Route::prefix('api')->name('api.')->group(function () {
        // Verificar estado de código QR
        Route::get('/qr-devices/{qrDevice}/status', [CashierController::class, 'checkCodeStatus'])->name('qr-device.status');
        
        // Heartbeat de dispositivos
        Route::post('/devices/heartbeat', [CashierController::class, 'heartbeat'])->name('devices.heartbeat');
        
        // Estadísticas del dashboard
        Route::get('/dashboard/stats', [ClientDashboardController::class, 'getStats'])->name('dashboard.stats');
    });

    // Transacciones
    Route::prefix('transactions')->name('client.transactions.')->group(function () {
        // Route::get('/', [TransactionController::class, 'index'])->name('index');
        // Route::get('/today', [TransactionController::class, 'today'])->name('today');
        // Route::get('/pending', [TransactionController::class, 'pending'])->name('pending');
        // Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        
        // Rutas temporales para evitar errores 404
        Route::get('/', function() { return redirect()->route('client.dashboard'); })->name('index');
        Route::get('/today', function() { return redirect()->route('client.dashboard'); })->name('today');
        Route::get('/pending', function() { return redirect()->route('client.dashboard'); })->name('pending');
    });
    
    // Reportes
    Route::prefix('reports')->name('client.reports.')->group(function () {
        // Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        // Route::get('/by-device', [ReportController::class, 'byDevice'])->name('by-device');
        // Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        
        // Rutas temporales
        Route::get('/daily', function() { return redirect()->route('client.dashboard'); })->name('daily');
        Route::get('/by-device', function() { return redirect()->route('client.dashboard'); })->name('by-device');
        Route::get('/monthly', function() { return redirect()->route('client.dashboard'); })->name('monthly');
    });

    // Configuración del Perfil
    Route::prefix('profile')->name('client.profile.')->group(function () {
        // Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        // Route::put('/update', [ProfileController::class, 'update'])->name('update');
        
        // Ruta temporal
        Route::get('/edit', function() { return redirect()->route('client.dashboard'); })->name('edit');
    });

    // Configuración del Sistema 
    Route::prefix('settings')->name('client.settings.')->group(function () {
        // Route::get('/devices', [SettingsController::class, 'devices'])->name('devices');
        // Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        
        // Rutas temporales
        Route::get('/devices', function() { return redirect()->route('client.dashboard'); })->name('devices');
        Route::get('/notifications', function() { return redirect()->route('client.dashboard'); })->name('notifications');
    });
});
