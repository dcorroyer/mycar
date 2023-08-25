<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-get
 * @group vehicule-feature
 * @group vehicule-get-feature
 */
class GetVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();

        $this->route = route('vehicule.show', ['vehicule' => $this->vehicule]);
    }

    /** @test */
    public function it_can_get_a_vehicule_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk()
            ->assertJson([
                'identification' => $this->vehicule->identification,
            ]);
    }
}
