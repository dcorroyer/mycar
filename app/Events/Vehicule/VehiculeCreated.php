<?php

namespace App\Events\Vehicule;

use App\Models\Vehicule;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehiculeCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var \App\Models\Vehicule $vehicule
     */
    protected Vehicule $vehicule;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Vehicule $vehicule
     */
    public function __construct(Vehicule $vehicule)
    {
        $this->vehicule = $vehicule;
    }
}
