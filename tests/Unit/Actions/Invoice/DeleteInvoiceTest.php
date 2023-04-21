<?php

namespace Tests\Unit\Actions\Invoice;

use App\Actions\Invoice\DeleteInvoice;
use App\Events\Invoice\InvoiceDeleted;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group invoice
 * @group invoice-delete
 * @group invoice-unit
 * @group invoice-delete-unit
 */
class DeleteInvoiceTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(InvoiceDeleted::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()->owner($this->user)->create();
        $this->maintenance = Maintenance::factory()->vehicule($this->vehicule)->create();
        $this->file = UploadedFile::fake()->image('image-1.png');
        $this->invoice = Invoice::factory()->file($this->file)->maintenance($this->maintenance)->create();
    }

    /** @test */
    public function it_can_delete_an_invoice_feature()
    {
        Storage::fake('s3');
        Storage::disk('s3')->put('/', $this->file);

        Storage::disk('s3')->assertExists($this->file->hashName());

        DeleteInvoice::run($this->invoice);

        Storage::disk('s3')->assertMissing($this->file->hashName());

        Event::assertDispatched(InvoiceDeleted::class);
    }
}
