<?php

namespace Tests\Unit\Actions\Invoice;

use App\Actions\Invoice\CreateInvoice;
use App\Events\Invoice\InvoiceCreated;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group invoice
 * @group invoice-create
 * @group invoice-unit
 * @group invoice-create-unit
 */
class CreateInvoiceTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(InvoiceCreated::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()->owner($this->user)->create();
        $this->maintenance = Maintenance::factory()->vehicule($this->vehicule)->create();

        $this->attributes = [
            'file' => UploadedFile::fake()->image('image-1.png'),
            'maintenance_uuid' => $this->maintenance->uuid,
        ];
    }

    /** @test */
    public function it_can_create_an_invoice_unit()
    {
        Storage::fake('s3');

        $invoice = CreateInvoice::run($this->attributes, $this->maintenance);

        Storage::disk('s3')->assertExists($invoice->path);

        Event::assertDispatched(InvoiceCreated::class);
    }
}
