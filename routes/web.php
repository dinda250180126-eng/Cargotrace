<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserController;

// Mengarahkan halaman utama (/) langsung ke halaman login bawaan Laravel (Auth)
Route::get('/', function () {
    return redirect()->route('login');
});

// RUTE DASHBOARD SUDAH DIPERBARUI UNTUK MENGIRIM DATA STATISTIK & GRAFIK
Route::get('/dashboard', function () {
    // 1. Ambil 5 data pengiriman terakhir
    $recentShipments = \App\Models\Shipment::with(['origin', 'destination'])->latest()->take(5)->get();
    
    // 2. Hitung jumlah pengiriman berdasarkan statusnya (untuk Rincian & Grafik)
    $statusBreakdown = \App\Models\Shipment::select('status', \DB::raw('count(*) as count'))
                        ->groupBy('status')
                        ->pluck('count', 'status');

    return view('dashboard', compact('recentShipments', 'statusBreakdown'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua rute di dalam grup ini WAJIB login terlebih dahulu
Route::middleware('auth')->group(function () {
    // Profile Routes (Bawaan auth)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/track-shipment', [ShipmentController::class, 'track'])->name('shipments.track');

    // Modul Shipments
    Route::get('/shipments/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/shipments/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
    
    // Rute Baru: Detail dan Hapus 
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::delete('/shipments/{shipment}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');

    // Modul Carriers (Ekspedisi Kargo) - DILENGKAPI
    Route::get('/carriers', [CarrierController::class, 'index'])->name('carriers.index');
    Route::post('/carriers', [CarrierController::class, 'store'])->name('carriers.store');
    Route::put('/carriers/{carrier}', [CarrierController::class, 'update'])->name('carriers.update');
    Route::delete('/carriers/{carrier}', [CarrierController::class, 'destroy'])->name('carriers.destroy');

    // Modul Locations (Lokasi Bandara) - DILENGKAPI
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

    // Modul Manajemen User (Administrator)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Modul Audit Log (CCTV Admin)
    Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit_logs.index');
});

require __DIR__.'/auth.php';