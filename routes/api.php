<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// --- PUBLIC ROUTES (Bisa diakses tanpa token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Wajib pakai token Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Shipments
    Route::get('/shipments', [App\Http\Controllers\Api\ShipmentController::class, 'index']);
    Route::post('/shipments', [App\Http\Controllers\Api\ShipmentController::class, 'store']);
    Route::get('/shipments/{id}', [App\Http\Controllers\Api\ShipmentController::class, 'show']);
    Route::put('/shipments/{id}', [App\Http\Controllers\Api\ShipmentController::class, 'update']);
    Route::patch('/shipments/{id}', [App\Http\Controllers\Api\ShipmentController::class, 'update']);
    Route::delete('/shipments/{id}', [App\Http\Controllers\Api\ShipmentController::class, 'destroy']);
    
    // Shipment Tracking
    Route::get('/shipments/{id}/tracking', [App\Http\Controllers\Api\ShipmentController::class, 'getTracking']);
    Route::post('/shipments/{id}/tracking', [App\Http\Controllers\Api\ShipmentController::class, 'updateTracking']);
    // Shipment ETA & Delay
    Route::get('/shipments/{id}/eta', [App\Http\Controllers\Api\ShipmentController::class, 'checkEta']);

    // Locations
    Route::get('/locations', [App\Http\Controllers\Api\LocationController::class, 'index']);
    Route::post('/locations', [App\Http\Controllers\Api\LocationController::class, 'store']);
    
    // Carriers
    Route::get('/carriers', [App\Http\Controllers\Api\CarrierController::class, 'index']);
    Route::post('/carriers', [App\Http\Controllers\Api\CarrierController::class, 'store']);
    
    // Exceptions
    Route::get('/shipments/{id}/exceptions', [App\Http\Controllers\Api\ExceptionController::class, 'index']);
    Route::post('/shipments/{id}/exceptions', [App\Http\Controllers\Api\ExceptionController::class, 'store']);
    Route::patch('/exceptions/{id}', [App\Http\Controllers\Api\ExceptionController::class, 'update']);
    
});