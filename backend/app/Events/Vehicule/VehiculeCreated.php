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
     * @var Vehicule $vehicule
     */
    protected Vehicule $vehicule;

    /**
     * Create a new event instance.
     *
     * @param Vehicule $vehicule
     */
    public function __construct(Vehicule $vehicule)
    {
        $this->vehicule = $vehicule;
    }
}
