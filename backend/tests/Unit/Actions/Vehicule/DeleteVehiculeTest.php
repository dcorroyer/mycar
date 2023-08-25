<?php

namespace Tests\Unit\Actions\Vehicule;

use App\Actions\Vehicule\DeleteVehicule;
use App\Events\Vehicule\VehiculeDeleted;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-delete
 * @group vehicule-unit
 * @group vehicule-delete-unit
 */
class DeleteVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(VehiculeDeleted::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
    }

    /** @test */
    public function it_can_delete_a_vehicule_unit()
    {
        DeleteVehicule::run($this->vehicule);

        $this->assertSoftDeleted('vehicules', [
            'id' => $this->vehicule->id,
        ]);

        Event::assertDispatched(VehiculeDeleted::class);
    }
}
