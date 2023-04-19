<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\UpdateMaintenance;
use App\Events\Maintenance\MaintenanceUpdated;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-update
 * @group maintenance-unit
 * @group maintenance-update-unit
 */
class UpdateMaintenanceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(MaintenanceUpdated::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
        $this->maintenance = Maintenance::factory()
            ->vehicule($this->vehicule)
            ->create();
    }

    /** @test */
    public function it_can_update_a_maintenance_unit()
    {
        $data = [
            'amount' => 1000,
        ];

        $maintenance = UpdateMaintenance::run($this->maintenance, $data);

        $this->assertDatabaseHas('maintenances', [
            'id' => $maintenance->id,
            'amount' => $data['amount'],
        ]);

        Event::assertDispatched(MaintenanceUpdated::class);
    }
}
