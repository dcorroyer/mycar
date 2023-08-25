<?php

namespace Tests\Feature\Actions\Maintenance;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-update
 * @group maintenance-feature
 * @group maintenance-update-feature
 */
class UpdateMaintenanceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
        $this->maintenance = Maintenance::factory()
            ->vehicule($this->vehicule)
            ->create();

        $this->route = route('maintenance.update',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
            ],
        );
    }

    /** @test */
    public function it_can_update_a_maintenance_feature()
    {
        $data = [
            'amount' => 1000,
        ];

        $this->actingAs($this->user)
            ->patchJson($this->route, $data)
            ->assertOk()
            ->assertJson([
                'amount' => $data['amount'],
            ]);
    }
}
