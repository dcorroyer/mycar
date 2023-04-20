<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\DeleteMaintenance;
use App\Events\Maintenance\MaintenanceDeleted;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-delete
 * @group maintenance-unit
 * @group maintenance-delete-unit
 */
class DeleteMaintenanceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(MaintenanceDeleted::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
        $this->maintenance = Maintenance::factory()
            ->vehicule($this->vehicule)
            ->create();
    }

    /** @test */
    public function it_can_delete_a_maintenance_unit()
    {
        DeleteMaintenance::run($this->maintenance);

        $this->assertDatabaseMissing('maintenances', [
            'id' => $this->maintenance->id,
        ]);

        Event::assertDispatched(MaintenanceDeleted::class);
    }
}
