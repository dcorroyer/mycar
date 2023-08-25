<?php

namespace Tests\Feature\Actions\Maintenance;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-create
 * @group maintenance-feature
 * @group maintenance-create-feature
 */
class CreateMaintenanceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
        $this->maintenance = Maintenance::factory()
            ->make([
                'vehicule_uuid' => $this->vehicule->uuid,
            ])
            ->toArray();

        $this->route = route('maintenance.store', ['vehicule' => $this->vehicule]);
    }

    /** @test */
    public function it_can_create_a_maintenance_feature()
    {
        $this->actingAs($this->user)
            ->postJson($this->route, $this->maintenance)
            ->assertCreated()
            ->assertJson([
                'amount' => $this->maintenance['amount'],
            ]);
    }

    /** @test */
    public function it_cant_create_a_maintenance_bad_request_feature()
    {
        $maintenance = Maintenance::factory()
            ->vehicule($this->vehicule)
            ->make()
            ->toArray();

        $maintenance['type'] = 'butterfly';

        $this->actingAs($this->user)
            ->postJson($this->route, $maintenance)
            ->assertUnprocessable();
    }
}
