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
class GetInvoiceTest extends TestCase
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
        $this->invoice = Invoice::factory()
            ->maintenance($this->maintenance)
            ->create();

        $this->route = route('invoice.show',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
                'invoice' => $this->invoice,
            ],
        );
    }

    /** @test */
    public function it_can_get_an_invoice_feature()
    {
        Storage::fake('s3');

        $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk()
            ->assertJson([
                'name' => $this->invoice->name,
            ]);
    }
}
