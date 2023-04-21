<?php

namespace Tests\Feature\Actions\Invoice;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group invoice
 * @group invoice-create
 * @group invoice-feature
 * @group invoice-create-feature
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

        $this->user = User::factory()->create();
        $this->vehicule = Vehicule::factory()->owner($this->user)->create();
        $this->maintenance = Maintenance::factory()->vehicule($this->vehicule)->create();

        $this->attributes = [
            'file' => UploadedFile::fake()->image('image-1.png'),
            'maintenance_uuid' => $this->maintenance->uuid,
        ];

        $this->route = route('invoice.store',
            [
                'vehicule' => $this->vehicule,
                'maintenance' => $this->maintenance,
            ],
        );
    }

    /** @test */
    public function it_can_create_an_invoice_feature()
    {
        Storage::fake('s3');

        $file = $this->actingAs($this->user)
            ->postJson($this->route, $this->attributes)
            ->assertCreated();

        $file_url = $file->getData()->url;
        $file_path = basename($file_url);

        Storage::disk('s3')->assertExists($file_path);
    }
}
