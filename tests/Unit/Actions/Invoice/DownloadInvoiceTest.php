<?php

namespace Tests\Unit\Actions\Invoice;

use App\Actions\Invoice\DownloadInvoice;
use App\Events\Invoice\InvoiceDownloaded;
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
 * @group invoice-download
 * @group invoice-unit
 * @group invoice-download-unit
 */
class DownloadInvoiceTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(InvoiceDownloaded::class);

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()->owner($this->user)->create();
        $this->maintenance = Maintenance::factory()->vehicule($this->vehicule)->create();
        $this->file = UploadedFile::fake()->image('image-1.png');
        $this->invoice = Invoice::factory()->file($this->file)->maintenance($this->maintenance)->create();
    }

    /** @test */
    public function it_can_download_an_invoice_unit()
    {
        Storage::fake('s3');
        Storage::disk('s3')->put('/', $this->file);

        Storage::disk('s3')->assertExists($this->file->hashName());

        $response = DownloadInvoice::run($this->invoice);

        $this->assertTrue($response->getStatusCode() == 200);
        $this->assertTrue($response->headers->get('content-type') == 'image/png');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="' . $this->invoice->file_name . '"');

        Event::assertDispatched(InvoiceDownloaded::class);
    }
}
