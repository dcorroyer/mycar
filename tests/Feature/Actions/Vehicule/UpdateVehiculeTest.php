<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-update
 * @group vehicule-feature
 * @group vehicule-update-feature
 */
class UpdateVehiculeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->vehicule = Vehicule::factory()->create();
        $this->route = route('vehicule.update', ['vehicule' => $this->vehicule]);
    }

    /** @test */
    public function it_can_update_a_vehicule_by_route_feature()
    {
        $data = [
            'identification' => 'CR-725-AM',
        ];

        $this->patchJson($this->route, $data)
            ->assertOk()
            ->assertJson([
                'identification' => $data['identification'],
            ])
        ;
    }
}
