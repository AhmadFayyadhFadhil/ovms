<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of all vehicles
     */
    public function index(): JsonResponse
    {
        // Check authorization
        if (!Auth::user()->can('view-vehicle')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $vehicles = Vehicle::all();

        return response()->json([
            'status' => 'success',
            'data' => VehicleResource::collection($vehicles)
        ], 200);
    }

    /**
     * Store a newly created vehicle
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => new VehicleResource($vehicle)
        ], 201);
    }

    /**
     * Display the specified vehicle
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        // Check authorization
        if (!Auth::user()->can('view-vehicle')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => new VehicleResource($vehicle)
        ], 200);
    }

    /**
     * Update the specified vehicle
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Kendaraan berhasil diperbarui',
            'data' => new VehicleResource($vehicle)
        ], 200);
    }

    /**
     * Delete the specified vehicle
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        // Check authorization
        if (!Auth::user()->can('delete-vehicle')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $vehicle->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kendaraan berhasil dihapus'
        ], 200);
    }
}