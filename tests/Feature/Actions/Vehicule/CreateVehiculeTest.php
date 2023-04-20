<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\User;
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
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->make([
                'user_uuid' => $this->user->uuid,
            ])
            ->toArray();

        $this->route = route('vehicule.store');
    }

    /** @test */
    public function it_can_create_a_vehicule_feature()
    {
        $this->actingAs($this->user)
            ->postJson($this->route, $this->vehicule)
            ->assertCreated()
            ->assertJson([
                'identification' => $this->vehicule['identification'],
            ]);
    }

    /** @test */
    public function it_cant_create_a_vehicule_already_created_feature()
    {
        $vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();

        $this->actingAs($this->user)
            ->postJson($this->route, $vehicule->toArray())
            ->assertUnprocessable();
    }

    /** @test */
    public function it_cant_create_a_vehicule_bad_request_feature()
    {
        $vehicule = Vehicule::factory()
            ->owner($this->user)
            ->make()
            ->toArray();

        $vehicule['type'] = 'butterfly';

        $this->actingAs($this->user)
            ->postJson($this->route, $vehicule)
            ->assertUnprocessable();
    }
}
