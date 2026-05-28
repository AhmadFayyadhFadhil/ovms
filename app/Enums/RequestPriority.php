<?php

namespace App\Enums;

enum RequestPriority: string
{
    case NORMAL = 'normal';
    case URGENT = 'urgent';
    case CRITICAL = 'critical';
}
