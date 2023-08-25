<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-delete
 * @group vehicule-feature
 * @group vehicule-delete-feature
 */
class DeleteVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();

        $this->route = route('vehicule.destroy', ['vehicule' => $this->vehicule]);
    }

    /** @test */
    public function it_can_delete_a_vehicule_feature()
    {
        $this->actingAs($this->user)
            ->deleteJson($this->route)
            ->assertNoContent();

        $this->assertSoftDeleted('vehicules', [
            'id' => $this->vehicule->id
        ]);
    }
}
