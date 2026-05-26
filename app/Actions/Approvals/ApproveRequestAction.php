<?php

namespace App\Actions\Approvals;

use App\Models\Request;
use App\Models\RequestApproval;
use App\Enums\RequestStatus;
use Illuminate\Support\Facades\DB;
use Exception;

class ApproveRequestAction
{
    public function execute(Request $request, string $role, string $status, ?string $notes = null): Request
    {
        return DB::transaction(function () use ($request, $role, $status, $notes) {
            // Validate sequence
            if ($role === 'dept_head') {
                if ($request->status !== RequestStatus::SUBMITTED) {
                    throw new Exception("Request cannot be approved by Department Head because it is not in submitted status.");
                }
                $newStatus = $status === 'approved' ? RequestStatus::APPROVED_DEPARTMENT : RequestStatus::REJECTED;
            } elseif ($role === 'hrd_head') {
                if ($request->status !== RequestStatus::APPROVED_DEPARTMENT) {
                    throw new Exception("Request must be approved by Department Head first.");
                }
                $newStatus = $status === 'approved' ? RequestStatus::APPROVED_HRD_GA : RequestStatus::REJECTED;
            } else {
                throw new Exception("Invalid approver role.");
            }

            // Create approval record
            RequestApproval::create([
                'request_id' => $request->id,
                'approver_id' => auth()->id(),
                'role' => $role,
                'status' => $status,
                'notes' => $notes,
            ]);

            // Update request status
            $request->update(['status' => $newStatus]);

            return $request;
        });
    }
}
