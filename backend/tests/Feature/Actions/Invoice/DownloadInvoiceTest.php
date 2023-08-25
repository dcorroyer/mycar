<?php

namespace Tests\Feature\Actions\Invoice;

use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group invoice
 * @group invoice-download
 * @group invoice-feature
 * @group invoice-download-feature
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

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()->owner($this->user)->create();
        $this->maintenance = Maintenance::factory()->vehicule($this->vehicule)->create();
        $this->file = UploadedFile::fake()->image('image-1.png');
        $this->invoice = Invoice::factory()->file($this->file)->maintenance($this->maintenance)->create();

        $this->route = route('invoice.download',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
                'invoice' => $this->invoice,
            ],
        );
    }

    /** @test */
    public function it_can_download_an_invoice_feature()
    {
        Storage::fake('s3');
        Storage::disk('s3')->put('/', $this->file);

        Storage::disk('s3')->assertExists($this->file->hashName());

        $response = $this->actingAs($this->user)
            ->getJson($this->route)
            ->assertOk();

        $this->assertTrue($response->getStatusCode() == 200);
        $this->assertTrue($response->headers->get('content-type') == 'image/png');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="' . $this->invoice->file_name . '"');
    }
}
