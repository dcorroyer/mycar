<?php

namespace Tests\Feature\Actions\Maintenance;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-get
 * @group maintenance-feature
 * @group maintenance-get-feature
 */
class GetMaintenanceTest extends TestCase
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

        $this->route = route('maintenance.show',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
            ],
        );
    }

    /** @test */
    public function it_can_get_a_maintenance_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk()
            ->assertJson([
                'type' => $this->maintenance->type,
            ])
        ;
    }
}
