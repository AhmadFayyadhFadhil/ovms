<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\VehicleController;

// Get current authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // ===== REQUEST ENDPOINTS =====
    // List requests (user's own or all if authorized)
    Route::get('/requests', [RequestController::class, 'index']);
    
    // Create new request
    Route::post('/requests', [RequestController::class, 'store']);
    
    // Show specific request
    Route::get('/requests/{request}', [RequestController::class, 'show']);
    
    // Update specific request
    Route::put('/requests/{request}', [RequestController::class, 'update']);
    Route::patch('/requests/{request}', [RequestController::class, 'update']);
    
    // Delete specific request
    Route::delete('/requests/{request}', [RequestController::class, 'destroy']);
    
    // Approval endpoints
    Route::post('/requests/{request}/approve', [RequestController::class, 'approve']);
    Route::post('/requests/{request}/reject', [RequestController::class, 'reject']);
    
    // ===== VEHICLE ENDPOINTS =====
    // List all vehicles
    Route::get('/vehicles', [VehicleController::class, 'index']);
    
    // Create new vehicle
    Route::post('/vehicles', [VehicleController::class, 'store']);
    
    // Show specific vehicle
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
    
    // Update specific vehicle
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::patch('/vehicles/{vehicle}', [VehicleController::class, 'update']);
    
    // Delete specific vehicle
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy']);
});