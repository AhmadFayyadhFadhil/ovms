<?php

namespace App\Enums;

enum DriverStatus: string
{
    case AVAILABLE = 'available';
    case ASSIGNED = 'assigned';
    case UNAVAILABLE = 'unavailable';
}
