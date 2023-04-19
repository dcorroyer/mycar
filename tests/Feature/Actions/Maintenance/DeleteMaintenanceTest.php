<?php

namespace Tests\Feature\Actions\Maintenance;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-delete
 * @group maintenance-feature
 * @group maintenance-delete-feature
 */
class DeleteMaintenanceTest extends TestCase
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

        $this->route = route('maintenance.destroy',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
            ],
        );
    }

    /** @test */
    public function it_can_delete_a_maintenance_feature()
    {
        $this->actingAs($this->user)
            ->deleteJson($this->route)
            ->assertNoContent();
    }
}
