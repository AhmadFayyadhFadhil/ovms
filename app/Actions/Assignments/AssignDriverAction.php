<?php

namespace App\Actions\Assignments;

use App\Models\Request;
use App\Models\Assignment;
use App\Enums\RequestStatus;
use Illuminate\Support\Facades\DB;
use Exception;

class AssignDriverAction
{
    public function execute(Request $request, int $driverId, ?string $notes = null): Assignment
    {
        return DB::transaction(function () use ($request, $driverId, $notes) {
            // Lock request row to prevent race condition
            $request = Request::where('id', $request->id)->lockForUpdate()->first();

            if ($request->status !== RequestStatus::APPROVED_HRD_GA) {
                throw new Exception("Request must be approved by HRD & GA first before assigning a driver.");
            }

            // Validate driver availability status
            $driver = \App\Models\User::findOrFail($driverId);
            if (($driver->availability_status ?? 'available') !== 'available') {
                throw new Exception("Driver yang dipilih sedang tidak tersedia atau sedang bertugas.");
            }

            // Create assignment
            $assignment = Assignment::create([
                'request_id' => $request->id,
                'driver_id' => $driverId,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'status' => 'pending_driver', // waiting for driver to accept/reject
                'notes' => $notes,
            ]);

            // Update request fields and status
            $request->update([
                'status' => RequestStatus::WAITING_DRIVER,
                'driver_id' => $driverId,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'driver_response_status' => 'pending_driver',
            ]);

            return $assignment;
        });
    }
}
