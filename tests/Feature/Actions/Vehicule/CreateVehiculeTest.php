<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-create
 * @group vehicule-feature
 * @group vehicule-create-feature
 */
class CreateVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->vehicule = Vehicule::factory()->make()->toArray();
        $this->route = route('vehicule.store');
    }

    /** @test */
    public function it_can_create_a_vehicule_feature()
    {
        $this->postJson($this->route, $this->vehicule)
            ->assertCreated()
            ->assertJson([
                'identification' => $this->vehicule['identification'],
            ])
        ;
    }
}
