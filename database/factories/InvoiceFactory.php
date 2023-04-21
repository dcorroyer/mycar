<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Document ' . fake()->word(),
            'file_name' => fake()->name(),
            'mime_type' => fake()->name(),
            'disk' => fake()->name(),
            'path' => fake()->filePath(),
            'size' => fake()->randomFloat('2', 0, 200),
        ];
    }

    /**
     * Define the disk as s3.
     *
     * @return InvoiceFactory
     */
    public function s3(): InvoiceFactory
    {
        return $this->state(fn() => ['disk' => 's3']);
    }

    /**
     * Define the model as pdf.
     *
     * @return InvoiceFactory
     */
    public function pdf(): InvoiceFactory
    {
        return $this->state(fn() => ['mime_type' => 'application/pdf']);
    }

    /**
     * Define the model as image.
     *
     * @return InvoiceFactory
     */
    public function image(): InvoiceFactory
    {
        return $this->state(fn() => ['mime_type' => 'image/jpeg']);
    }

    /**
     * Define the model as file.
     *
     * @param UploadedFile $file
     *
     * @return InvoiceFactory
     */
    public function file(UploadedFile $file): InvoiceFactory
    {
        return $this->state(fn() => [
            'mime_type' => $file->getMimeType(),
            'path' => $file->hashName(),
            'disk' => 's3',
        ]);
    }

    /**
     * @param Maintenance $maintenance
     *
     * @return InvoiceFactory
     */
    public function maintenance(Maintenance $maintenance): InvoiceFactory
    {
        return $this->state(fn () => [
            'maintenance_id' => $maintenance->id,
        ]);
    }
}
