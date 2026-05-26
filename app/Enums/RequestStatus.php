<?php

namespace App\Enums;

enum RequestStatus: string
{
    case SUBMITTED = 'submitted';
    case APPROVED_DEPARTMENT = 'approved_department';
    case APPROVED_HRD_GA = 'approved_hrd_ga';
    case WAITING_DRIVER = 'waiting_driver';
    case DRIVER_ASSIGNED = 'driver_assigned';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
}
