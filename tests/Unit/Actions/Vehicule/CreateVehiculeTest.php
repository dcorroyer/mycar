<?php

namespace Tests\Unit\Actions\Vehicule;

use App\Actions\Vehicule\CreateVehicule;
use App\Events\Vehicule\VehiculeCreated;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-create
 * @group vehicule-unit
 * @group vehicule-create-unit
 */
class CreateVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(VehiculeCreated::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->make()
            ->toArray();
    }

    /** @test */
    public function it_can_create_a_vehicule_unit()
    {
        $vehicule = CreateVehicule::run($this->vehicule, $this->user);

        $this->assertDatabaseHas('vehicules', [
            'id' => $vehicule->id,
            'identification' => $vehicule->identification,
        ]);

        Event::assertDispatched(VehiculeCreated::class);
    }
}
