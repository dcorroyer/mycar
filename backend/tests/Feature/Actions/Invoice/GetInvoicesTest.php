<?php

namespace Tests\Feature\Actions\Invoice;

use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group invoice
 * @group invoice-get
 * @group invoice-feature
 * @group invoice-get-feature
 */
class GetInvoicesTest extends TestCase
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
        $this->invoices = Invoice::factory(10)
            ->maintenance($this->maintenance)
            ->create();

        $this->route = route('invoice.index',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
            ],
        );
    }

    /** @test */
    public function it_can_get_invoices_feature()
    {
        Storage::fake('s3');

        $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk()
            ->assertJsonCount(10);
    }

    /** @test */
    public function it_can_get_invoices_with_filter_feature()
    {
        Storage::fake('s3');

        $this->actingAs($this->user)
            ->getJson($this->route . '?filter[name]=' . $this->invoices->first()->name)
            ->assertOk();
    }
}
