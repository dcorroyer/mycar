<?php

namespace Tests\Feature\Actions\Vehicule;

use App\Models\User;
use App\Models\Vehicule;
use Tests\TestCase;

/**
 * @group vehicule
 * @group vehicule-get
 * @group vehicule-feature
 * @group vehicule-get-feature
 */
class GetVehiculesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->vehicules = Vehicule::factory(10)
            ->owner($this->user)
            ->create();

        $this->route = route('vehicule.index');
    }

    /** @test */
    public function it_can_get_vehicules_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk()
            ->assertJsonCount(10);
    }

    /** @test */
    public function it_can_get_vehicules_with_filter_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->route . '?filter[identification]=' . $this->vehicules->first()->identification)
            ->assertOk();
    }
}
