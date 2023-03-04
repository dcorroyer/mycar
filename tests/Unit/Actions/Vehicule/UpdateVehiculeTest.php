<?php

namespace Tests\Unit\Actions\Vehicule;

use App\Actions\Vehicule\UpdateVehicule;
use App\Events\Vehicule\VehiculeUpdated;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-update
 * @group vehicule-unit
 * @group vehicule-update-unit
 */
class UpdateVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(VehiculeUpdated::class);
        $this->vehicule = Vehicule::factory()->create();
    }

    /** @test */
    public function it_can_update_a_vehicule_unit()
    {
        $data = [
            'identification' => 'CR-725-AM',
        ];

        $vehicule = UpdateVehicule::run($this->vehicule, $data);

        $this->assertDatabaseHas('vehicules', [
            'id' => $vehicule->id,
            'identification' => $data['identification'],
        ]);

        Event::assertDispatched(VehiculeUpdated::class);
    }
}
