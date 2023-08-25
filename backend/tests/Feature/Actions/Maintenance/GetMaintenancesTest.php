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
class GetMaintenancesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()
            ->owner($this->user)
            ->create();
        $this->maintenances = Maintenance::factory(10)
            ->vehicule($this->vehicule)
            ->create();

        $this->route = route('maintenance.index', ['vehicule' => $this->vehicule]);
    }

    /** @test */
    public function it_can_get_maintenances_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk()
            ->assertJsonCount(10);
    }

    /** @test */
    public function it_can_get_maintenances_with_filter_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->route . '?filter[type]=' . $this->maintenances->first()->type)
            ->assertOk();
    }
}
