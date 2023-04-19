<?php

namespace App\Events\Maintenance;

use App\Models\Maintenance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceDeleted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var Maintenance $maintenance
     */
    protected Maintenance $maintenance;

    /**
     * Create a new event instance.
     *
     * @param Maintenance $maintenance
     */
    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
    }
}
