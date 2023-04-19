<?php

namespace App\Enums\Maintenance;

enum MaintenanceTypes: string
{
    case Maintenance = 'maintenance';
    case Repair = 'repair';
    case Restoration = 'restoration';
}
