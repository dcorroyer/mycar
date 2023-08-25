<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\User;
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
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();

        $this->route = route('vehicule.update', ['vehicule' => $this->vehicule]);
    }

    /** @test */
    public function it_can_update_a_vehicule_feature()
    {
        $data = [
            'identification' => 'CR-725-AM',
        ];

        $this->actingAs($this->user)
            ->patchJson($this->route, $data)
            ->assertOk()
            ->assertJson([
                'identification' => $data['identification'],
            ]);
    }
}
