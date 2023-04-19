<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\CreateMaintenance;
use App\Events\Maintenance\MaintenanceCreated;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group maintenance
 * @group maintenance-create
 * @group maintenance-unit
 * @group maintenance-create-unit
 */
class CreateMaintenanceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(MaintenanceCreated::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
        $this->maintenance = Maintenance::factory()
            ->make([
                'vehicule_uuid' => $this->vehicule->uuid,
            ])
            ->toArray();
    }

    /** @test */
    public function it_can_create_a_maintenance_unit()
    {
        $maintenance = CreateMaintenance::run($this->maintenance, $this->vehicule);

        $this->assertDatabaseHas('maintenances', [
            'id' => $maintenance->id,
            'amount' => $maintenance->amount,
        ]);

        Event::assertDispatched(MaintenanceCreated::class);
    }
}
